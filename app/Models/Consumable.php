<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    protected $fillable = [

        'name',
        'unit_measure_id',
        'minimum_stock'

    ];

    /**
     * Relasi satuan barang
     */
    public function unitMeasure()
    {
        return $this->belongsTo(
            UnitMeasure::class,
            'unit_measure_id'
        );
    }

    /**
     * Relasi transaksi barang
     */
    public function transactions()
    {
        return $this->hasMany(
            ConsumableTransaction::class
        );
    }
}