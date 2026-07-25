<?php

namespace App\Services;

use App\Models\Consumable;
use App\Models\ConsumableStock;
use App\Models\ConsumableTransaction;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConsumableService
{
    private function hasContinuousColumns(): bool
    {
        return Schema::hasColumn('consumables', 'inventory_type')
            && Schema::hasColumn('consumable_stocks', 'inventory_type')
            && Schema::hasColumn('consumable_stocks', 'remaining_length')
            && Schema::hasColumn('consumable_stocks', 'initial_length');
    }

    private function isContinuousItem(?Consumable $item): bool
    {
        return $item
            && $this->hasContinuousColumns()
            && $item->inventory_type === 'CONTINUOUS';
    }

    public function generateItemCode($categoryId = null, $itemNumber = null)
    {
        $category = Category::find($categoryId) ?: Category::firstOrCreate(
            ['code' => 'ATK'],
            ['name' => 'ATK']
        );

        $number = $this->normaliseItemNumber($itemNumber ?: $this->nextItemNumber($category->id));

        return strtoupper($category->code) . '-' . $number;
    }

    public function normaliseItemNumber($itemNumber): string
    {
        return str_pad((string) ((int) $itemNumber), 6, '0', STR_PAD_LEFT);
    }

    public function nextItemNumber($categoryId): string
    {
        $category = Category::findOrFail($categoryId);
        $prefix = strtoupper($category->code) . '-';
        $usedNumbers = Consumable::where('category_id', $category->id)
            ->get(['item_number', 'item_code'])
            ->map(function ($item) use ($prefix) {
                if ($item->item_number !== null && ctype_digit((string) $item->item_number)) {
                    return (int) $item->item_number;
                }

                return str_starts_with((string) $item->item_code, $prefix)
                    ? (int) substr($item->item_code, strlen($prefix))
                    : null;
            })
            ->filter()
            ->flip();

        $number = 1;
        while ($usedNumbers->has($number)) {
            $number++;
        }

        return $this->normaliseItemNumber($number);
    }

    public function defaultCategoryId()
    {
        return Category::firstOrCreate(
            ['code' => 'ATK'],
            ['name' => 'ATK']
        )->id;
    }

    public function getStock($consumableId)
    {
        $item = Consumable::find($consumableId);

        if ($this->isContinuousItem($item)) {
            return (float) ConsumableStock::where('consumable_id', $consumableId)
                ->sum('remaining_length');
        }

        return (int) ConsumableStock::where('consumable_id', $consumableId)
            ->sum('quantity');
    }

    public function stockSummary(Consumable $item): array
    {
        if ($this->isContinuousItem($item)) {
            $stocks = $item->relationLoaded('stocks') ? $item->stocks : $item->stocks()->get();
            $rolls = (int) $stocks->where('roll_count', '>', 0)->where('remaining_length', '>', 0)->sum('roll_count');

            return [
                'type' => 'CONTINUOUS',
                'rolls' => $rolls,
                'initial_length' => (float) $stocks->sum('initial_length'),
                'remaining_length' => (float) $stocks->sum('remaining_length'),
                'unit' => $stocks->firstWhere('length_unit')?->length_unit ?: $item->unitMeasure?->name,
            ];
        }

        return [
            'type' => 'UNIT',
            'quantity' => (int) $this->getStock($item->id),
            'unit' => $item->unitMeasure?->name,
        ];
    }

    public function formatStock(Consumable $item): string
    {
        $summary = $this->stockSummary($item);

        if ($summary['type'] === 'CONTINUOUS') {
            $amount = number_format($summary['remaining_length'], 2) . ' ' . ($summary['unit'] ?: '');

            if ($summary['rolls'] > 0) {
                return number_format($summary['rolls']) . ' Roll / ' . $amount;
            }

            return $amount;
        }

        return number_format($summary['quantity']) . ' ' . ($summary['unit'] ?: '');
    }

    public function canTakeStock($consumableId, $qty)
    {
        $stock = $this->getStock($consumableId);

        return $stock >= $qty;
    }

    public function getOrCreateStockLocation($consumableId, $locationId = null)
    {
        $locationId = $locationId ?: $this->defaultLocationId();

        return ConsumableStock::firstOrCreate(
            [
                'consumable_id' => $consumableId,
                'location_id' => $locationId,
            ],
            [
                'quantity' => 0,
            ]
        );
    }

    public function defaultLocationId()
    {
        return Location::firstOrCreate(
            ['name' => 'Gudang Utama'],
            ['description' => 'Lokasi default untuk stok awal dan data lama.']
        )->id;
    }

    public function addStock($consumableId, $qty, $note = null, $locationId = null, array $batch = [])
    {
        $item = Consumable::with('unitMeasure')->findOrFail($consumableId);

        if ($item->inventory_type === 'CONTINUOUS') {
            return $this->addContinuousStock($item, $note, $locationId, $batch);
        }

        return DB::transaction(function () use ($consumableId, $qty, $note, $locationId, $batch) {
            $stockLocation = $this->getOrCreateStockLocation($consumableId, $locationId);
            $stockLocation->fill(array_filter([
                'brand' => $batch['brand'] ?? null,
                'model' => $batch['model'] ?? null,
                'serial_number' => $batch['serial_number'] ?? null,
                'specification' => $batch['specification'] ?? null,
                'condition' => $batch['condition'] ?? null,
                'inventory_type' => 'UNIT',
            ], fn ($value) => !is_null($value) && $value !== ''));
            $stockLocation->save();
            $stockLocation->increment('quantity', $qty);

            return ConsumableTransaction::create([
                'consumable_id' => $consumableId,
                'consumable_stock_id' => $stockLocation->id,
                'type' => 'IN',
                'quantity' => $qty,
                'note' => $note
            ]);
        });
    }

    public function addContinuousStock(Consumable $item, $note = null, $locationId = null, array $batch = [])
    {
        $lengthAmount = (float) ($batch['length_amount'] ?? $batch['quantity'] ?? 0);
        $lengthUnit = $batch['length_unit'] ?? $item->unitMeasure?->name;

        if ($lengthAmount <= 0) {
            throw new \Exception('Jumlah wajib lebih dari 0');
        }

        return DB::transaction(function () use ($item, $note, $locationId, $batch, $lengthAmount, $lengthUnit) {
            $locationId = $locationId ?: $this->defaultLocationId();

            $stock = ConsumableStock::where('consumable_id', $item->id)
                ->where('location_id', $locationId)
                ->lockForUpdate()
                ->first();

            if ($stock) {
                $stock->fill(array_filter([
                    'batch_code' => $stock->batch_code ?: $lengthUnit,
                    'brand' => $batch['brand'] ?? null,
                    'model' => $batch['model'] ?? null,
                    'serial_number' => $batch['serial_number'] ?? null,
                    'specification' => $batch['specification'] ?? null,
                    'condition' => $batch['condition'] ?? $item->condition,
                    'inventory_type' => 'CONTINUOUS',
                    'roll_count' => 0,
                    'length_unit' => $stock->length_unit ?: $lengthUnit,
                ], fn ($value) => !is_null($value) && $value !== ''));
                $stock->initial_length = (float) ($stock->initial_length ?? 0) + $lengthAmount;
                $stock->remaining_length = (float) ($stock->remaining_length ?? 0) + $lengthAmount;
                $stock->quantity = 0;
                $stock->save();
            } else {
                $stock = ConsumableStock::create([
                    'consumable_id' => $item->id,
                    'location_id' => $locationId,
                    'batch_code' => $lengthUnit,
                    'quantity' => 0,
                    'brand' => $batch['brand'] ?? null,
                    'model' => $batch['model'] ?? null,
                    'serial_number' => $batch['serial_number'] ?? null,
                    'specification' => $batch['specification'] ?? null,
                    'condition' => $batch['condition'] ?? $item->condition,
                    'inventory_type' => 'CONTINUOUS',
                    'roll_count' => 0,
                    'initial_length' => $lengthAmount,
                    'remaining_length' => $lengthAmount,
                    'length_unit' => $lengthUnit,
                ]);
            }

            return ConsumableTransaction::create([
                'consumable_id' => $item->id,
                'consumable_stock_id' => $stock->id,
                'type' => 'IN',
                'quantity' => 0,
                'length_amount' => $lengthAmount,
                'length_unit' => $lengthUnit,
                'note' => $note,
            ]);
        });
    }

    public function transferStock($consumableId, $fromLocationId, $toLocationId, $qty, $note = null, $userId = null, $stockBatchId = null)
    {
        if ($qty < 1) {
            throw new \Exception('Jumlah transfer harus lebih dari 0');
        }

        if ($fromLocationId == $toLocationId) {
            throw new \Exception('Lokasi asal dan tujuan tidak boleh sama');
        }

        $item = Consumable::findOrFail($consumableId);

        if ($item->inventory_type === 'CONTINUOUS') {
            return DB::transaction(function () use ($consumableId, $fromLocationId, $toLocationId, $note, $userId, $stockBatchId) {
                $sourceStock = ConsumableStock::where('consumable_id', $consumableId)
                    ->where('id', $stockBatchId)
                    ->where('location_id', $fromLocationId)
                    ->where('inventory_type', 'CONTINUOUS')
                    ->lockForUpdate()
                    ->first();

                if (!$sourceStock || $sourceStock->remaining_length <= 0) {
                    throw new \Exception('Roll asal tidak valid atau kosong');
                }

                $sourceStock->update(['location_id' => $toLocationId]);

                return ConsumableTransaction::create([
                    'consumable_id' => $consumableId,
                    'consumable_stock_id' => $sourceStock->id,
                    'type' => 'TRANSFER',
                    'quantity' => 0,
                    'length_amount' => $sourceStock->remaining_length,
                    'length_unit' => $sourceStock->length_unit,
                    'note' => $note,
                    'from_location_id' => $fromLocationId,
                    'to_location_id' => $toLocationId,
                    'user_id' => $userId,
                ]);
            });
        }

        return DB::transaction(function () use ($consumableId, $fromLocationId, $toLocationId, $qty, $note, $userId) {
            $sourceStock = ConsumableStock::where('consumable_id', $consumableId)
                ->where('location_id', $fromLocationId)
                ->lockForUpdate()
                ->first();

            if (!$sourceStock || $sourceStock->quantity < $qty) {
                throw new \Exception('Stok asal tidak mencukupi');
            }

            $destinationStock = ConsumableStock::firstOrCreate(
                [
                    'consumable_id' => $consumableId,
                    'location_id' => $toLocationId,
                ],
                ['quantity' => 0]
            );

            $sourceStock->decrement('quantity', $qty);
            $destinationStock->increment('quantity', $qty);

            return ConsumableTransaction::create([
                'consumable_id' => $consumableId,
                'consumable_stock_id' => $destinationStock->id,
                'type' => 'TRANSFER',
                'quantity' => $qty,
                'note' => $note,
                'from_location_id' => $fromLocationId,
                'to_location_id' => $toLocationId,
                'user_id' => $userId,
            ]);
        });
    }

    public function takeStock($consumableId, $qty, $note = null, $locationId = null, $stockBatchId = null)
    {
        $item = Consumable::findOrFail($consumableId);

        if ($item->inventory_type === 'CONTINUOUS') {
            return $this->takeContinuousStock($item, (float) $qty, $note, $locationId, $stockBatchId);
        }

        if (!$this->canTakeStock($consumableId, $qty)) {
            throw new \Exception('Stok tidak mencukupi');
        }

        return DB::transaction(function () use ($consumableId, $qty, $note, $locationId) {
            $remaining = $qty;
            $transaction = null;

            $query = ConsumableStock::where('consumable_id', $consumableId)
                ->where('quantity', '>', 0)
                ->orderBy('id');

            if ($locationId) {
                $query->where('location_id', $locationId);
            }

            $stocks = $query->lockForUpdate()->get();

            if ($stocks->sum('quantity') < $qty) {
                throw new \Exception('Stok tidak mencukupi');
            }

            foreach ($stocks as $stockLocation) {
                if ($remaining <= 0) {
                    break;
                }

                $taken = min($stockLocation->quantity, $remaining);
                $stockLocation->decrement('quantity', $taken);
                $remaining -= $taken;

                $transaction = ConsumableTransaction::create([
                    'consumable_id' => $consumableId,
                    'consumable_stock_id' => $stockLocation->id,
                    'type' => 'OUT',
                    'quantity' => $taken,
                    'note' => $note
                ]);
            }

            return $transaction;
        });
    }

    public function takeContinuousStock(Consumable $item, float $length, $note = null, $locationId = null, $stockBatchId = null)
    {
        if ($length <= 0) {
            throw new \Exception('Panjang yang digunakan harus lebih dari 0');
        }

        return DB::transaction(function () use ($item, $length, $note, $locationId, $stockBatchId) {
            $query = ConsumableStock::where('consumable_id', $item->id)
                ->where('inventory_type', 'CONTINUOUS')
                ->where('remaining_length', '>', 0);

            if ($stockBatchId) {
                $query->where('id', $stockBatchId);
            }

            if ($locationId) {
                $query->where('location_id', $locationId);
            }

            $stock = $query->lockForUpdate()->orderBy('id')->first();

            if (!$stock || $stock->remaining_length < $length) {
                throw new \Exception('Panjang tersisa tidak mencukupi');
            }

            $stock->decrement('remaining_length', $length);

            return ConsumableTransaction::create([
                'consumable_id' => $item->id,
                'consumable_stock_id' => $stock->id,
                'type' => 'OUT',
                'quantity' => 0,
                'length_amount' => $length,
                'length_unit' => $stock->length_unit,
                'note' => $note,
            ]);
        });
    }
}
