<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom untuk integrasi Google Analytics 4 (GA4 Data API) di Dashboard CMS.
     * Diisi nanti saat website sudah live — sebelum diisi, dashboard cukup
     * menampilkan status "belum terhubung" (lihat GoogleAnalyticsService).
     *
     * - ga4_property_id      : Property ID GA4, contoh "properties/123456789".
     * - ga4_credentials_path : path file JSON service account relatif terhadap
     *                          storage/app (disimpan di luar folder public untuk keamanan).
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('ga4_property_id')->nullable()->after('footer_description');
            $table->string('ga4_credentials_path')->nullable()->after('ga4_property_id');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['ga4_property_id', 'ga4_credentials_path']);
        });
    }
};
