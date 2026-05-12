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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no')->unique();

            // কাস্টমার তথ্য (ইউজার প্রোফাইল পরিবর্তন করলেও অর্ডারের হিস্টোরি যেন ঠিক থাকে)
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');

            // পেমেন্ট তথ্য
            $table->string('payment_method')->nullable(); // Cash on Delivery, SSLCommerz, etc.
            $table->string('transaction_id')->nullable()->unique();

            // হিসাব-নিকাশ
            $table->decimal('total_amount', 10, 2);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);

            // স্ট্যাটাস
            $table->string('status')->default('pending'); // pending, confirmed, processing, picked, shipped, delivered, cancelled
            $table->string('payment_status')->default('unpaid');

            $table->text('order_notes')->nullable(); // কাস্টমারের কোনো বিশেষ অনুরোধ থাকলে
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};