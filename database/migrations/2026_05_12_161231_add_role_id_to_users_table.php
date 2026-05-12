<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ১. role_id কলাম যোগ করা যা roles টেবিলের সাথে যুক্ত
            // এটি অবশ্যই 'after' ব্যবহার করে ইমেইল বা পাসওয়ার্ডের পরে রাখা ভালো
            $table->foreignId('role_id')->after('password')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ২. ড্রপ করার সময় আগে ফরেন কি ড্রপ করতে হয়, তারপর কলাম
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};