<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Standard Laravel property use kora-i best practice.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // role_id ekhane thaka khub-i joruri
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Role এর সাথে রিলেশন (বাকি সব ঠিক আছে)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Admin check korar logic
     */
    public function isAdmin()
    {
        // $this->role object ti loading thaka obosthay check korbe
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