<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableTransaction extends Model
{
    protected $fillable = [

        'consumable_id',
        'type',
        'quantity',
        'note'

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