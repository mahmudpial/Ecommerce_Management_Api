<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    /**
     * যে কলামগুলো মাস-অ্যাসাইনমেন্ট (Mass Assignment) করা যাবে।
     * অর্থাৎ create() বা update() মেথডে এই ডাটাগুলো পাঠানো যাবে।
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'status',
    ];

    /**
     * রিলেশনশিপ: একটি ব্র্যান্ডের অধীনে অনেকগুলো প্রোডাক্ট থাকতে পারে।
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}