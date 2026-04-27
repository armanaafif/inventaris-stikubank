<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    protected $fillable = [
        'consumable_id',
        'quantity',
        'type',
        'note',
        'status',
        'user_id'
    ];
}
