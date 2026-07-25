<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UnitMeasure;
use App\Models\ConsumableTransaction;
use App\Models\Borrowing;
use App\Models\ConsumableStock;

class Consumable extends Model
{
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Informasi Barang
        |--------------------------------------------------------------------------
        */

        'name',
        'inventory_type',
        'item_code',
        'item_number',
        'category_id',
        'brand',
        'model',
        'serial_number',
        'specification',
        'description',

        /*
        |--------------------------------------------------------------------------
        | Relasi
        |--------------------------------------------------------------------------
        */

        'unit_measure_id',

        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */

        'minimum_stock',

        /*
        |--------------------------------------------------------------------------
        | Kondisi & Status
        |--------------------------------------------------------------------------
        */

        'condition',
        'status',
        'purchase_receipt_path'

    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi Satuan Barang
    |--------------------------------------------------------------------------
    */

    public function unitMeasure()
    {
        return $this->belongsTo(
            UnitMeasure::class,
            'unit_measure_id'
        );
    }

    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Transaksi Barang
    |--------------------------------------------------------------------------
    */

    public function transactions()
    {
        return $this->hasMany(
            ConsumableTransaction::class
        );
    }

    public function stocks()
    {
        return $this->hasMany(ConsumableStock::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Peminjaman Barang
    |--------------------------------------------------------------------------
    | Menghubungkan model Consumable dengan Borrowing
    | Satu consumable bisa memiliki banyak peminjaman
    */

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
