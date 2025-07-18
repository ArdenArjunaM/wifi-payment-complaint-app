<?php
// database/migrations/xxxx_xx_xx_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->string('order_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('denda', 10, 2)->default(0);
            $table->enum('status', ['pending', 'success', 'challenge', 'denied', 'expired', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('tagihan_id')->references('id_tagihan')->on('tagihan')->onDelete('cascade');
            $table->index(['order_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};