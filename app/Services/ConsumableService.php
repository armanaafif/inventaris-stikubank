<?php

namespace App\Services;

use App\Models\ConsumableTransaction;

class ConsumableService
{
    public function getStock($consumableId)
    {
        $in = ConsumableTransaction::where('consumable_id', $consumableId)
            ->where('type', 'IN')
            ->sum('quantity');

        $out = ConsumableTransaction::where('consumable_id', $consumableId)
            ->where('type', 'OUT')
            ->sum('quantity');

        return $in - $out;
    }

    public function canTakeStock($consumableId, $qty)
    {
        $stock = $this->getStock($consumableId);

        return $stock >= $qty;
    }

        //funtion in (tambah stok)
    public function addStock($consumableId, $qty, $note = null)
    {
    return ConsumableTransaction::create([
        'consumable_id' => $consumableId,
        'type' => 'IN',
        'quantity' => $qty,
        'note' => $note
    ]);
    }

    //function out (pakai barang)
    public function takeStock($consumableId, $qty, $note = null)
{
    if ($qty <= 0) {
        throw new \Exception('Jumlah harus lebih dari 0');
    }

    if (!$this->canTakeStock($consumableId, $qty)) {
        throw new \Exception('Stok tidak mencukupi');
    }

    return ConsumableTransaction::create([
        'consumable_id' => $consumableId,
        'type' => 'OUT',
        'quantity' => $qty,
        'note' => $note
    ]);
    }
}