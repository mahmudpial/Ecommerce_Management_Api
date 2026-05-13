<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // কন্ট্রোলারের ডাটা অনুযায়ী এই ফিল্ডগুলো অবশ্যই থাকতে হবে
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'total_price'
    ];

    // রিলেশনশিপ
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}