<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // ১. Mass Assignment allow korar jonno field gulo define kora
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // ২. Relationship: একটি কার্ট আইটেম নির্দিষ্ট একজন ইউজারের
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ৩. Relationship: কার্ট আইটেমের ভেতরে কোন প্রোডাক্ট আছে তা জানার জন্য
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ৪. Mentor Tip: Subtotal Attribute
    // প্রোডাক্টিভ প্রাইস আর কোয়ান্টিটি গুণ করে অটোমেটিক সাব-টোটাল বের করার জন্য
    public function getSubtotalAttribute()
    {
        return $this->product->price * $this->quantity;
    }
}