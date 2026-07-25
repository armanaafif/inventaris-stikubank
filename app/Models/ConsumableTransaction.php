<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ConsumableTransaction extends Model
{
    protected $fillable = [

        'consumable_id',
        'consumable_stock_id',
        'type',
        'quantity',
        'length_amount',
        'length_unit',
        'note',
        'from_location_id',
        'to_location_id',
        'user_id'

    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi Barang
    |--------------------------------------------------------------------------
    */

    public function consumable()
    {
        return $this->belongsTo(
            Consumable::class
        );
    }

    public function consumableStock()
    {
        return $this->belongsTo(
            ConsumableStock::class
        );
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Barang Masuk
    |--------------------------------------------------------------------------
    */

    public function scopeIn($query)
    {
        return $query->where('type', 'IN');
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Barang Keluar
    |--------------------------------------------------------------------------
    */

    public function scopeOut($query)
    {
        return $query->where('type', 'OUT');
    }
}
