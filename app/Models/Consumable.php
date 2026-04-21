<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    
    protected $fillable = [
        'name',
        'unit_id',
        'unit_measure_id',
        'minimum_stock'
    ];

    public function unitMeasure()
{
    return $this->belongsTo(UnitMeasure::class);
}

public function transactions()
{
    return $this->hasMany(ConsumableTransaction::class);
}
}


