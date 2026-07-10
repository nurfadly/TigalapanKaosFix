<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Jembatan ke Google Analytics 4 Data API supaya statistik pengunjung website
 * bisa tampil langsung di Dashboard CMS (bukan cuma di analytics.google.com).
 *
 * STATUS SEKARANG (belum live):
 * Class ini SIAP DIPAKAI tapi belum aktif karena dua hal berikut belum diisi:
 *   1. Composer package resminya belum ter-install:
 *        composer require google/analytics-data
 *   2. Kolom ga4_property_id & ga4_credentials_path di menu Pengaturan CMS masih kosong.
 *
 * Selama salah satu dari itu belum ada, semua method di bawah otomatis balikin
 * null/placeholder (lihat isAvailable()) — TIDAK error, TIDAK bikin dashboard rusak.
 *
 * CARA MENGAKTIFKAN SAAT WEBSITE SUDAH LIVE (ringkas, detail lengkap ada di
 * file GOOGLE-ANALYTICS-SETUP.md di root project):
 *   1. Buat Property GA4 di analytics.google.com, catat Property ID (angka).
 *   2. Di Google Cloud Console: buat project, aktifkan "Google Analytics Data API",
 *      buat Service Account, download file JSON credentials-nya.
 *   3. Di GA4 Admin > Property Access Management, tambahkan email service account
 *      tadi sebagai Viewer.
 *   4. Upload file JSON tsb ke server, simpan di storage/app/google/ga4-service-account.json
 *      (folder ini TIDAK boleh public, sudah otomatis di-gitignore lewat storage/app/.gitignore bawaan Laravel).
 *   5. composer require google/analytics-data
 *   6. Buka menu Pengaturan di CMS, isi "GA4 Property ID" (mis. properties/123456789)
 *      dan "Path Credentials" (mis. google/ga4-service-account.json).
 *   7. Refresh Dashboard — card "Traffic Website" otomatis menampilkan data asli.
 */
class GoogleAnalyticsService
{
    private Setting $settings;

    public function __construct(?Setting $settings = null)
    {
        $this->settings = $settings ?? Setting::current();
    }

    /**
     * Cek apakah integrasi sudah bisa jalan: package resmi ter-install DAN
     * kredensial + property ID sudah diisi di Pengaturan.
     */
    public function isAvailable(): bool
    {
        return class_exists(\Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient::class)
            && $this->settings->isGa4Configured()
            && Storage::disk('local')->exists($this->settings->ga4_credentials_path);
    }

    /**
     * Ringkasan traffic N hari terakhir: pengunjung (activeUsers), sesi, dan pageview.
     * Balikin null kalau belum dikonfigurasi — Dashboard akan menampilkan placeholder.
     *
     * @return array{visitors:int, sessions:int, pageviews:int}|null
     */
    public function getSummary(int $days = 7): ?array
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            // Catatan implementasi saat composer package sudah terpasang:
            //
            // $client = new \Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient([
            //     'credentials' => Storage::disk('local')->path($this->settings->ga4_credentials_path),
            // ]);
            // $response = $client->runReport([
            //     'property' => $this->settings->ga4_property_id,
            //     'dateRanges' => [new \Google\Analytics\Data\V1beta\DateRange([
            //         'start_date' => "{$days}daysAgo",
            //         'end_date' => 'today',
            //     ])],
            //     'metrics' => [
            //         new \Google\Analytics\Data\V1beta\Metric(['name' => 'activeUsers']),
            //         new \Google\Analytics\Data\V1beta\Metric(['name' => 'sessions']),
            //         new \Google\Analytics\Data\V1beta\Metric(['name' => 'screenPageViews']),
            //     ],
            // ]);
            // $row = $response->getRows()[0] ?? null;
            // return $row ? [
            //     'visitors' => (int) $row->getMetricValues()[0]->getValue(),
            //     'sessions' => (int) $row->getMetricValues()[1]->getValue(),
            //     'pageviews' => (int) $row->getMetricValues()[2]->getValue(),
            // ] : null;

            return null;
        } catch (Throwable $e) {
            Log::warning('GoogleAnalyticsService::getSummary gagal: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Halaman paling banyak dikunjungi dalam N hari terakhir.
     *
     * @return array<int, array{path:string, views:int}>|null
     */
    public function getTopPages(int $days = 7, int $limit = 5): ?array
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            // Implementasi nyata memanggil BetaAnalyticsDataClient::runReport()
            // dengan dimension "pagePath" dan metric "screenPageViews", diurutkan
            // menurun lalu diambil sejumlah $limit baris teratas. Lihat komentar
            // di getSummary() untuk pola pemanggilan client-nya.

            return null;
        } catch (Throwable $e) {
            Log::warning('GoogleAnalyticsService::getTopPages gagal: '.$e->getMessage());

            return null;
        }
    }
}
