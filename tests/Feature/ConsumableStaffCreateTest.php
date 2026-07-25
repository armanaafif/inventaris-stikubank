<?php

use App\Models\UnitMeasure;
use App\Models\User;
use App\Models\Category;
use App\Models\Consumable;
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

test('item number creates a category-prefixed unique code and approval retains master fields', function () {
    $staff = User::factory()->create(['role' => 'staff', 'status' => 'approved']);
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'approved']);
    $unit = UnitMeasure::create(['name' => 'UNIT']);
    $category = Category::create(['name' => 'Elektronik Uji', 'code' => 'ELU']);

    $this->actingAs($staff)->post(route('barang.store'), [
        'name' => 'Proyektor Uji',
        'category_id' => $category->id,
        'item_number' => '152',
        'brand' => 'Epson',
        'model' => 'EB-X06',
        'serial_number' => 'SN-001',
        'specification' => '3.600 lumens',
        'description' => 'Unit pengujian',
        'unit_measure_id' => $unit->id,
        'initial_stock' => 1,
        'condition' => 'BARU',
        'status' => 'AKTIF',
    ]);

    $request = \App\Models\StockRequest::firstOrFail();
    expect($request->item_code)->toBe('ELU-000152');

    $this->actingAs($admin)->post(route('admin.requests.approve', $request));

    $this->assertDatabaseHas('consumables', [
        'item_code' => 'ELU-000152',
        'item_number' => '000152',
        'brand' => 'Epson',
        'model' => 'EB-X06',
        'serial_number' => 'SN-001',
        'specification' => '3.600 lumens',
        'description' => 'Unit pengujian',
    ]);

    $this->actingAs($admin)->post(route('barang.store'), [
        'name' => 'Barang Admin', 'category_id' => $category->id, 'item_number' => '000001',
        'brand' => 'Logitech', 'unit_measure_id' => $unit->id, 'initial_stock' => 0, 'condition' => 'BARU', 'status' => 'AKTIF',
    ])->assertRedirect(route('barang.index'));

    $this->assertDatabaseHas('consumables', ['item_code' => 'ELU-000001', 'brand' => 'Logitech']);

    $this->actingAs($admin)->post(route('barang.store'), [
        'name' => 'Duplikat', 'category_id' => $category->id, 'item_number' => '000152',
        'unit_measure_id' => $unit->id, 'initial_stock' => 0, 'condition' => 'BARU', 'status' => 'AKTIF',
    ])->assertSessionHasErrors(['item_number' => 'Nomor barang sudah digunakan.']);

    expect(app(\App\Services\ConsumableService::class)->nextItemNumber($category->id))->toBe('000002');
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
