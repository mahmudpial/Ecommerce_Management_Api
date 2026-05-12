<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ফরেন কি চেক সাময়িকভাবে বন্ধ করে টেবিল খালি করা (নিরাপদ থাকার জন্য)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // রোলগুলো তৈরি করা
        Role::create(['id' => 1, 'name' => 'Admin']);
        Role::create(['id' => 2, 'name' => 'Manager']);
        Role::create(['id' => 3, 'name' => 'User']);
    }
}