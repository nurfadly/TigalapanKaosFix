<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu baris per nama item unik dari export POS (kolom "Name - Variant"),
     * dipisah dari stock_items supaya pencocokan ke Produk katalog (matched_product_id)
     * cukup dilakukan sekali per item, bukan diulang untuk tiap outlet.
     * Tabel ini TIDAK ikut ditimpa saat upload baru — hanya angka stok di
     * stock_items yang diganti, supaya hasil pencocokan produk tidak hilang.
     */
    public function up(): void
    {
        Schema::create('stock_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('raw_name')->unique();
            $table->string('category')->nullable();
            $table->foreignId('matched_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->float('match_score')->nullable();
            $table->boolean('matched_manually')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_catalog');
    }
};
