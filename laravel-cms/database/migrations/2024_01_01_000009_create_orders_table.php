<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pesanan yang masuk lewat checkout di website (cart.js).
     * Tetap membuka WhatsApp seperti biasa, tapi datanya juga disimpan
     * di sini supaya admin bisa pantau & tindak lanjuti dari CMS.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->unsignedBigInteger('total');
            $table->enum('status', ['new', 'processing', 'completed', 'cancelled'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
