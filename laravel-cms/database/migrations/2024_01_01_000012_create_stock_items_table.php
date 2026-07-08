<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Angka stok per item per outlet. Tabel ini yang DITIMPA TOTAL setiap
     * kali admin upload file stok baru (lihat StockController@store).
     */
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_catalog_id')->constrained('stock_catalog')->cascadeOnDelete();
            $table->string('outlet');
            $table->integer('beginning')->default(0);
            $table->integer('purchase_order')->default(0);
            $table->integer('sales')->default(0);
            $table->integer('transfer')->default(0);
            $table->integer('adjustment')->default(0);
            $table->integer('ending')->default(0);
            $table->timestamps();

            $table->unique(['stock_catalog_id', 'outlet']);
            $table->index('outlet');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
