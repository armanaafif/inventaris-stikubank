<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
    protected $fillable = [

        'name'

    ];

    /**
     * Relasi ke barang
     */
    public function consumables()
    {
        return $this->hasMany(
            Consumable::class,
            'unit_measure_id'
        );
    }
}