<?php

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
    $response->assertSessionHas('approval_pending', 'Barang berhasil diajukan dan sedang menunggu approval admin.');

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

    $this->assertDatabaseMissing('consumables', [
        'name' => 'Kabel HDMI',
    ]);
});

test('legacy barang store url still submits create item requests', function () {
    $staff = User::factory()->create([
        'role' => 'staff',
        'status' => 'approved',
    ]);

    $unit = UnitMeasure::create([
        'name' => 'BOX',
    ]);

    $response = $this
        ->actingAs($staff)
        ->post('/barang/store', [
            'name' => 'Spidol Boardmarker',
            'unit_measure_id' => $unit->id,
            'minimum_stock' => 3,
            'initial_stock' => 12,
            'condition' => 'BARU',
            'status' => 'AKTIF',
        ]);

    $response->assertRedirect('/barang');
    $response->assertSessionHas('approval_pending', 'Barang berhasil diajukan dan sedang menunggu approval admin.');

    $this->assertDatabaseHas('stock_requests', [
        'request_type' => 'CREATE_ITEM',
        'item_name' => 'Spidol Boardmarker',
        'status' => 'pending',
    ]);
});
