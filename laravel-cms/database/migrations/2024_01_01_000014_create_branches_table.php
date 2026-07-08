<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kantor pusat & cabang, ditampilkan di halaman Tentang dan Kontak
     * (dulu hardcoded: Makassar/Samarinda/Kendari/Manado).
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('province');
            $table->boolean('is_hq')->default(false);
            // Opsional, override label default ("Kantor Pusat" / "Cabang") kalau perlu istilah lain.
            $table->string('label')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
