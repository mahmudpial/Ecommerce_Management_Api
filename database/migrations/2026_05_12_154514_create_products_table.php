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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // [cite: 62]
            // এটি 'categories' টেবিলের সাথে সম্পর্ক তৈরি করে 
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // [cite: 63]
            $table->decimal('price', 10, 2); // [cite: 64]
            $table->integer('stock'); // [cite: 65]
            $table->string('image')->nullable(); // [cite: 66]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
