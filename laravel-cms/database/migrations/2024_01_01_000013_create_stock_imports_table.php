<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat ringkasan tiap kali file stok diupload (bukan data mentahnya,
     * cukup ringkasan: nama file, jumlah baris, jumlah outlet, dst).
     * Baris di sini TIDAK PERNAH dihapus otomatis saat upload baru,
     * jadi admin bisa lihat riwayat upload dari waktu ke waktu.
     */
    public function up(): void
    {
        Schema::create('stock_imports', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->unsignedInteger('total_rows');
            $table->unsignedInteger('total_outlets');
            $table->unsignedInteger('total_items');
            $table->unsignedInteger('total_matched')->default(0);
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_imports');
    }
};
