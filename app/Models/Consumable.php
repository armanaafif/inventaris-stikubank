<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    protected $fillable = [
        'name',
        'unit_id',
        'minimum_stock'
    ];

    public function unitMeasure()
    {
        return $this->belongsTo(UnitMeasure::class, 'unit_id');
    }

    public function transactions()
    {
        return $this->hasMany(ConsumableTransaction::class);
    }
}