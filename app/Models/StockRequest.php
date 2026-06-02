<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Consumable;
use App\Models\User;
use App\Models\UnitMeasure;

class StockRequest extends Model
{
    protected $fillable = [
        /*
        |--------------------------------------------------------------------------
        | Request
        |--------------------------------------------------------------------------
        */

        'request_type',

        /*
        |--------------------------------------------------------------------------
        | Stock Request
        |--------------------------------------------------------------------------
        */

        'consumable_id',
        'quantity',
        'type',

        /*
        |--------------------------------------------------------------------------
        | Item Request
        |--------------------------------------------------------------------------
        */

        'item_name',
        'unit_measure_id',
        'minimum_stock',
        'initial_stock',
        'condition',
        'item_status',

        /*
        |--------------------------------------------------------------------------
        | General
        |--------------------------------------------------------------------------
        */

        'note',
        'status',
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
            Consumable::class,
            'consumable_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi User
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Satuan
    |--------------------------------------------------------------------------
    */

    public function unitMeasure()
    {
        return $this->belongsTo(
            UnitMeasure::class,
            'unit_measure_id'
        );
    }
}