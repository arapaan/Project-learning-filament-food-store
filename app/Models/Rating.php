<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'transaction_detail_id',
        'rating',
        'review',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
