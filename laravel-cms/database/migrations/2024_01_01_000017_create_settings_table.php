<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Konfigurasi situs (satu baris saja, id=1) — nomor WA, email, sosial media, dll.
     * Menggantikan nilai yang dulu hardcoded di site-layout.blade.php dan halaman lain.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('tigalapankaos');
            $table->string('whatsapp_number')->default('6280000000000');
            $table->string('contact_email')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_province')->nullable();
            $table->text('footer_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
