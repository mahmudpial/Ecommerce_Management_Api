<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'price',
        'stock',
        'image',
        'description'
    ];

    // এপিআই রেসপন্সে অটোমেটিক ইমেজের ফুল ইউআরএল পাঠানোর জন্য
    protected $appends = ['image_url'];

    /**
     * Accessor: ইমেজের পূর্ণাঙ্গ পাথ তৈরি করা
     * এর ফলে ডাটাবেসে শুধু নাম থাকলেও আপনি এপিআই-তে সরাসরি লিঙ্ক পাবেন।
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // যদি ইমেজটি URL হয় (লিঙ্ক) তবে সেটিই দেখাবে, না হলে স্টোরেজ থেকে পাথ নেবে
            return filter_var($this->image, FILTER_VALIDATE_URL)
                ? $this->image
                : asset('storage/' . $this->image);
        }

        // ডিফল্ট ইমেজ (যদি কোনো ছবি না থাকে)
        return asset('images/no-image.png');
    }

    /**
     * Relationship: প্রডাক্ট কোন ক্যাটাগরির?
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: প্রডাক্ট কোন ব্র্যান্ডের?
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Scope: স্টক আউট প্রডাক্ট ফিল্টার করার জন্য (ঐচ্ছিক কিন্তু দরকারি)
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}