<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableStock extends Model
{
    protected $fillable = [
        'consumable_id',
        'location_id',
        'batch_code',
        'quantity',
        'brand',
        'model',
        'serial_number',
        'specification',
        'condition',
        'inventory_type',
        'roll_count',
        'initial_length',
        'remaining_length',
        'length_unit',
    ];

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
