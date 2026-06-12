<?php

use App\Models\StockRequest;
use App\Models\UnitMeasure;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('staff can submit a create item request', function () {
    $staff = User::factory()->create([
        'role' => 'staff',
        'status' => 'approved',
    ]);

    $unit = UnitMeasure::create([
        'name' => 'PCS',
    ]);

    $response = $this
        ->actingAs($staff)
        ->post(route('barang.store'), [
            'name' => 'Kabel HDMI',
            'unit_measure_id' => $unit->id,
            'minimum_stock' => 2,
            'initial_stock' => 5,
            'condition' => 'BARU',
            'status' => 'AKTIF',
        ]);

    $response->assertRedirect('/barang');

    $this->assertDatabaseHas('stock_requests', [
        'request_type' => 'CREATE_ITEM',
        'user_id' => $staff->id,
        'item_name' => 'Kabel HDMI',
        'unit_measure_id' => $unit->id,
        'minimum_stock' => 2,
        'initial_stock' => 5,
        'condition' => 'BARU',
        'item_status' => 'AKTIF',
        'status' => 'pending',
    ]);

    expect(StockRequest::first()->consumable_id)->toBeNull();
});
