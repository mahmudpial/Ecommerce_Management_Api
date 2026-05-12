<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    // একটি রোলের অনেক ইউজার থাকতে পারে
    public function users()
    {
        return $this->hasMany(User::class);
    }
}