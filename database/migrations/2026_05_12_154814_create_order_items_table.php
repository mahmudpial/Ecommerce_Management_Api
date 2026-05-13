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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // setNull ব্যবহার করা ভালো যদি আপনি চান প্রোডাক্ট ডিলিট হলেও অর্ডারের ডাটা থেকে যাক
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name'); // অর্ডার করার সময় প্রোডাক্টের নাম কি ছিল (ব্যাকআপের জন্য)
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // প্রতি ইউনিটের দাম
            $table->decimal('total_price', 10, 2); // quantity * unit_price (রিপোর্টের জন্য সুবিধা)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};