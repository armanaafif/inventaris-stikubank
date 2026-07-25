<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'building',
        'floor',
        'room',
        'description',
        'is_active',
    ];

    public function consumableStocks()
    {
        return $this->hasMany(ConsumableStock::class);
    }
}
