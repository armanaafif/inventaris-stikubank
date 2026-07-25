<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Consumable;
use App\Models\User;
use App\Models\UnitMeasure;
use App\Models\Category;
use App\Models\Location;

class StockRequest extends Model
{
    protected $fillable = [
        /*
        |--------------------------------------------------------------------------
        | Request
        |--------------------------------------------------------------------------
        */

        'request_type',
        'item_code',
        'item_number',

        /*
        |--------------------------------------------------------------------------
        | Stock Request
        |--------------------------------------------------------------------------
        */

        'consumable_id',
        'quantity',
        'type',
        'from_location_id',
        'to_location_id',

        /*
        |--------------------------------------------------------------------------
        | Item Request
        |--------------------------------------------------------------------------
        */

        'item_name',
        'inventory_type',
        'brand',
        'model',
        'serial_number',
        'specification',
        'description',
        'category_id',
        'unit_measure_id',
        'location_id',
        'minimum_stock',
        'initial_stock',
        'roll_count',
        'roll_length',
        'length_amount',
        'length_unit',
        'condition',
        'item_status',
        'purchase_receipt_path',

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

    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    public function location()
    {
        return $this->belongsTo(
            Location::class,
            'location_id'
        );
    }

    public function fromLocation()
    {
        return $this->belongsTo(
            Location::class,
            'from_location_id'
        );
    }

    public function toLocation()
    {
        return $this->belongsTo(
            Location::class,
            'to_location_id'
        );
    }
}
