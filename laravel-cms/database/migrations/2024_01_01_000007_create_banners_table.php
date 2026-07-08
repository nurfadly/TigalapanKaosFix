<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Banner/hero slider, mengikuti 3 slide full-bleed yang sudah ada
     * di hero section index.html (gambar + judul + deskripsi + tombol CTA).
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description', 300)->nullable();
            $table->string('image');
            $table->string('cta_text')->nullable();
            $table->string('cta_link')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
