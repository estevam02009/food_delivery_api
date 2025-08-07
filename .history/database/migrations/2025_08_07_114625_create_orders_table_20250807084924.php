<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('restaurant_id')->constrained();
            $table->enum('status', ['pending', 'processing', 'delivered', 'delivering', 'canceled'])->default('pending');
            $table->string('delivery_addres');
            $table->decimal('total_price', 8, 2);
            $table->enum('payment_method', ['credit_card', 'debit_card', 'money']);
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
