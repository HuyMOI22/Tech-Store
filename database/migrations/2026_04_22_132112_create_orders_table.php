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
        $table->string('order_code')->unique();
        $table->enum('status', ['pending','confirmed','shipping','delivered','cancelled'])->default('pending');
        $table->decimal('total_amount', 12, 2);
        $table->string('shipping_name');
        $table->string('shipping_phone');
        $table->text('shipping_address');
        $table->string('payment_method');
        $table->enum('payment_status', ['unpaid','paid'])->default('unpaid');
        $table->text('note')->nullable();
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
