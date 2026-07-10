# Setup Google Analytics 4 di Dashboard CMS

Dokumen ini untuk diikuti **saat website Tigalapankaos sudah live** dan siap dipantau trafficnya. Sebelum langkah-langkah ini dijalankan, card "Traffic Website" di Dashboard CMS akan otomatis menampilkan status "Belum Terhubung" — ini normal dan tidak mengganggu fitur CMS lainnya.

Yang sudah disiapkan di kode (tidak perlu diubah lagi):

- Tabel `settings` sudah punya kolom `ga4_property_id` dan `ga4_credentials_path`.
- Menu Pengaturan di CMS sudah punya form untuk mengisi dua field tersebut.
- `App\Services\GoogleAnalyticsService` sudah siap memanggil GA4 Data API, tinggal package resminya diinstall.
- Dashboard sudah punya card "Traffic Website" yang otomatis beralih dari placeholder ke data asli begitu semua langkah di bawah selesai.

## Langkah 1 — Buat Property GA4

1. Buka [analytics.google.com](https://analytics.google.com), buat Property baru untuk domain Tigalapankaos (kalau belum ada).
2. Di Admin > Property Settings, catat **Property ID** (angka, contoh `123456789`).

## Langkah 2 — Buat Service Account di Google Cloud

1. Buka [console.cloud.google.com](https://console.cloud.google.com), buat project baru (atau pakai yang sudah ada).
2. Aktifkan API: cari **"Google Analytics Data API"** di API Library, klik Enable.
3. Buka **IAM & Admin > Service Accounts**, buat service account baru (nama bebas, misal `ga4-dashboard-reader`).
4. Setelah dibuat, buka tab **Keys** pada service account tsb, klik **Add Key > Create New Key > JSON**. File JSON akan otomatis terdownload — **simpan baik-baik, jangan diupload ke Git/publik**.

## Langkah 3 — Beri Akses Service Account ke Property GA4

1. Buka email service account dari file JSON tadi (formatnya seperti `xxxx@xxxx.iam.gserviceaccount.com`).
2. Di GA4 Admin > Property Access Management, klik "+" > Add users, masukkan email tsb dengan role **Viewer**.

## Langkah 4 — Upload Credentials ke Server

1. Upload file JSON dari Langkah 2 ke server, simpan di:
   ```
   storage/app/google/ga4-service-account.json
   ```
2. Pastikan folder ini **tidak** berada di `public/` dan tidak ter-commit ke Git (folder `storage/app` sudah otomatis di-gitignore oleh Laravel secara default).

## Langkah 5 — Install Package Composer

Jalankan di server:
```
composer require google/analytics-data
```

## Langkah 6 — Buka Kode GoogleAnalyticsService, Aktifkan Implementasi Asli

Buka `app/Services/GoogleAnalyticsService.php`. Di dalam method `getSummary()` dan `getTopPages()` ada blok komentar berisi contoh kode pemanggilan `BetaAnalyticsDataClient`. Uncomment blok tersebut (dan hapus baris `return null;` placeholder di bawahnya) — komentar sudah ditulis sesuai signature resmi package `google/analytics-data`, tinggal disesuaikan kalau ada perubahan versi API dari Google.

## Langkah 7 — Isi Menu Pengaturan di CMS

1. Login ke CMS, buka menu **Pengaturan**.
2. Isi:
   - **GA4 Property ID**: `properties/123456789` (pakai Property ID dari Langkah 1, dengan prefix `properties/`).
   - **Path Credentials (JSON)**: `google/ga4-service-account.json` (path relatif terhadap `storage/app`, sesuai Langkah 4).
3. Simpan.

## Langkah 8 — Selesai

Buka Dashboard — card "Traffic Website" akan otomatis menampilkan jumlah pengunjung, sesi, dan halaman terpopuler 7 hari terakhir. Kalau masih menampilkan "Belum Terhubung", cek:

- Apakah `composer require google/analytics-data` sudah berhasil (cek `vendor/google/analytics-data` ada).
- Apakah path file JSON di Pengaturan sudah persis sama dengan lokasi file di `storage/app/`.
- Apakah service account sudah punya akses Viewer di Property GA4 yang benar.
- Cek `storage/logs/laravel.log` — kalau ada error dari GA4 API, akan tercatat di sana dengan prefix "GoogleAnalyticsService".
