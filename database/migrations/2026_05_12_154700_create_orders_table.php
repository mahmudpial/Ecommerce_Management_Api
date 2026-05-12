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
            $table->foreignId('user_id')->constrained(); // Customer Information [cite: 83]
            $table->string('invoice_number')->unique(); // Invoice Number [cite: 88]
            $table->decimal('total_amount', 10, 2); // Total Amount [cite: 87]
            $table->string('order_status')->default('Pending'); // Pending, Processing, Delivered, Cancelled [cite: 76, 77, 79, 80, 81]
            $table->string('payment_status')->default('Unpaid'); // Payment Status [cite: 75]
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
