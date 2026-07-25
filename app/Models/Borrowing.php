<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [

        'consumable_id',
        'consumable_stock_id',
        'user_id',
        'borrower_name',
        'borrower_phone',
        'borrower_unit',
        'purpose',
        'quantity',
        'borrow_date',
        'return_date',
        'actual_return_date',
        'status',
        'note',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejected_by',
        'rejected_at',
        'returned_at'

    ];

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

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}
