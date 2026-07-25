<?php

use App\Models\Category;
use App\Models\Consumable;
use App\Models\ConsumableStock;
use App\Models\Location;
use App\Models\UnitMeasure;
use App\Models\User;
use App\Services\ConsumableService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can transfer stock between locations', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'status' => 'approved',
    ]);

    $unit = UnitMeasure::create(['name' => 'PCS']);
    $category = Category::firstOrCreate(['code' => 'ELK'], ['name' => 'Elektronik']);
    $item = Consumable::create([
        'name' => 'Mouse Wireless',
        'item_code' => 'ELK-00001',
        'category_id' => $category->id,
        'unit_measure_id' => $unit->id,
        'minimum_stock' => 2,
        'condition' => 'BARU',
        'status' => 'AKTIF',
    ]);

    $source = Location::create(['name' => 'Gudang A', 'is_active' => true]);
    $destination = Location::create(['name' => 'Gudang B', 'is_active' => true]);

    app(ConsumableService::class)->addStock($item->id, 10, 'Stok awal', $source->id);

    $response = $this
        ->actingAs($admin)
        ->post(route('stock.transfer'), [
            'consumable_id' => $item->id,
            'from_location_id' => $source->id,
            'to_location_id' => $destination->id,
            'quantity' => 4,
            'note' => 'Pindah ke Gudang B',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('consumable_transactions', [
        'consumable_id' => $item->id,
        'type' => 'TRANSFER',
        'quantity' => 4,
        'note' => 'Pindah ke Gudang B',
    ]);

    $this->assertSame(6, ConsumableStock::where('consumable_id', $item->id)->where('location_id', $source->id)->value('quantity'));
    $this->assertSame(4, ConsumableStock::where('consumable_id', $item->id)->where('location_id', $destination->id)->value('quantity'));
});
