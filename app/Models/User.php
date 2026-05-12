<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;


#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    // ২. HasApiTokens ট্রেইটটি ব্যবহার করুন
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Role এর সাথে রিলেশন (প্রতিটি ইউজারের একটি রোল থাকে)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * এটি অ্যাডমিন কিনা চেক করার জন্য (সহজ করার জন্য)
     */
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'Admin';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}