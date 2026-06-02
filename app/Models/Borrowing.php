<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [

        'consumable_id',
        'user_id',
        'borrower_name',
        'quantity',
        'borrow_date',
        'return_date',
        'actual_return_date',
        'status',
        'note'

    ];

    public function consumable()
    {
        return $this->belongsTo(
            Consumable::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}