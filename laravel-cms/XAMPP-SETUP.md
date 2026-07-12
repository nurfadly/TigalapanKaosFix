# Panduan Setup CMS Tigalapankaos di XAMPP (Lokal)

Project yang dikirim berisi **kode aplikasi Laravel** (`app/`, `database/`, `resources/`, `routes/`, `public/cart.js`) — bukan instalasi Laravel lengkap. Jadi langkah pertama adalah bikin skeleton Laravel kosong lewat Composer, lalu install **Laravel Breeze** (untuk sistem login/register CMS), baru file-file yang dikirim ditimpakan/digabung ke dalamnya.

## 0. Yang Perlu Disiapkan

- **XAMPP** dengan PHP **8.1 atau 8.2** (unduh versi XAMPP yang sesuai di apachefriends.org — pilih installer yang menyertakan PHP 8.1.x/8.2.x, bukan yang PHP 8.0 ke bawah).
- **Composer** (unduh di getcomposer.org, install versi Windows/Mac/Linux sesuai OS).
- **Node.js + npm** (unduh di nodejs.org, pilih versi LTS). **Wajib**, bukan opsional — dipakai untuk meng-compile CSS/JS halaman login/register bawaan Laravel Breeze (lihat Langkah 3).
- Text editor apa saja (VS Code, dsb) — opsional, hanya untuk mengecek `.env`.

Cek PHP & Composer sudah kebaca di terminal:
```
php -v
composer -v
node -v
```
Kalau `php` belum kebaca, tambahkan folder `php` di dalam instalasi XAMPP (misal `C:\xampp\php`) ke PATH environment variable.

## 1. Nyalakan Apache & MySQL

Buka **XAMPP Control Panel**, klik **Start** di baris Apache dan MySQL. Pastikan keduanya berwarna hijau / statusnya "Running".

Kalau MySQL gagal start karena port 3306 bentrok (biasa kalau ada MySQL lain terpasang), ganti port MySQL lewat XAMPP Control Panel > Config, atau matikan service MySQL lain di komputer.

## 2. Buat Skeleton Laravel

Buka terminal (jangan di dalam folder `htdocs` dulu, cukup di folder kerja bebas), lalu:
```
composer create-project laravel/laravel tigalapankaos
cd tigalapankaos
```
Ini akan membuat instalasi Laravel lengkap (ada `composer.json`, `vendor/`, `config/`, `.env`, dst) di folder `tigalapankaos`.

## 3. Install Laravel Breeze (Wajib — Ini Sumber Error `routes/auth.php` Tidak Ditemukan)

Project ini **mengasumsikan Laravel Breeze (stack Blade) sudah terpasang**, karena `routes/web.php` yang dikirim punya baris `require __DIR__.'/auth.php';` di baris terakhir dan memakai `ProfileController` bawaan Breeze. Kalau langkah ini dilewati, akan muncul error seperti:
```
require(.../routes/auth.php): Failed to open stream: No such file or directory
```

Jalankan di dalam folder `tigalapankaos` (SEBELUM menyalin file dari `laravel-cms` di Langkah 4, supaya file Breeze tidak menimpa balik file kita):
```
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
```
Saat `breeze:install blade` berjalan, kalau ditanya soal testing framework (Pest/PHPUnit) pilih sesuai selera — tidak berpengaruh ke CMS. Perintah ini otomatis membuat: `routes/auth.php`, `app/Http/Controllers/Auth/*`, `app/Http/Controllers/ProfileController.php`, `resources/views/auth/*` (halaman login/register), dan `resources/views/profile/*` — semua file inilah yang bikin sistem login CMS jalan.

`npm run build` wajib dijalankan minimal sekali supaya CSS Tailwind bawaan Breeze ter-compile ke `public/build/` — tanpa ini, halaman login akan error "Vite manifest not found".

## 4. Gabungkan File yang Sudah Dikirim

Dari folder `laravel-cms` yang dikirim, salin/timpakan ke folder `tigalapankaos` hasil Langkah 2-3 (menimpa `routes/web.php` dan `app/Models/User.php` bawaan Breeze — ini memang disengaja, catatan penggabungan sudah ada di komentar masing-masing file):

| Dari (`laravel-cms/...`)            | Ke (`tigalapankaos/...`)             | Catatan |
|---|---|---|
| `app/Http/Controllers/*`            | `app/Http/Controllers/`              | Timpa/tambah semua (termasuk `Controller.php` dasar — jangan hapus folder `Auth/` dan `ProfileController.php` bikinan Breeze) |
| `app/Models/*`                      | `app/Models/`                        | Timpa/tambah semua (termasuk `User.php`, memang disengaja menimpa) |
| `app/Services/*`                    | `app/Services/`                      | Buat folder `Services` baru |
| `app/Providers/AppServiceProvider.php` | `app/Providers/AppServiceProvider.php` | **Timpa** file bawaan Laravel |
| `database/migrations/*`             | `database/migrations/`               | Tambahkan semua file migration (biarkan migration bawaan Breeze/Laravel yang sudah ada) |
| `database/seeders/*`                | `database/seeders/`                  | **Timpa** `DatabaseSeeder.php` bawaan, tambahkan seeder lain |
| `resources/views/*`                 | `resources/views/`                   | Timpa/tambah semua (termasuk folder `components`, `site`, `settings`, dll — jangan hapus folder `auth` dan `profile` bikinan Breeze) |
| `routes/web.php`                    | `routes/web.php`                     | **Timpa** file bawaan Breeze (baris `require __DIR__.'/auth.php'` di baris terakhir tetap dipertahankan) |
| `public/cart.js`                    | `public/cart.js`                     | File baru |

Kalau memakai terminal, dari luar kedua folder bisa pakai (sesuaikan path):
```
cp -r laravel-cms/app/. tigalapankaos/app/
cp -r laravel-cms/database/. tigalapankaos/database/
cp -r laravel-cms/resources/. tigalapankaos/resources/
cp laravel-cms/routes/web.php tigalapankaos/routes/web.php
cp laravel-cms/public/cart.js tigalapankaos/public/cart.js
```
(Di Windows pakai Robocopy atau salin manual lewat File Explorer kalau tidak familiar dengan command `cp`.)

## 5. Pindahkan Project ke `htdocs` (Opsional, lihat Langkah 8 dulu)

Kalau nanti mau akses lewat `http://localhost/...`, folder `tigalapankaos` perlu dipindah/disalin ke dalam `C:\xampp\htdocs\` (Windows) atau `/Applications/XAMPP/htdocs/` (Mac). Kalau memilih pakai `php artisan serve` (lebih simpel, direkomendasikan untuk development), lewati langkah ini — cukup taruh folder di mana saja.

## 6. Buat Database di phpMyAdmin

1. Buka browser ke `http://localhost/phpmyadmin`.
2. Klik **New**, buat database baru dengan nama `tigalapankaos`, collation `utf8mb4_unicode_ci`.

## 7. Konfigurasi `.env`

Buka file `.env` di root folder `tigalapankaos`, sesuaikan bagian database (default XAMPP: user `root`, password kosong):
```
APP_NAME=Tigalapankaos
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tigalapankaos
DB_USERNAME=root
DB_PASSWORD=
```
Kalau folder ditaruh di `htdocs` dan diakses lewat Apache, ubah `APP_URL` sesuai path-nya, misal `http://localhost/tigalapankaos/public`.

## 8. Install Dependency & Siapkan Aplikasi

Dari dalam folder `tigalapankaos`:
```
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```
- `migrate` membuat semua tabel (users, produk, kategori, artikel, pesanan, stok, leads, testimoni, cabang, settings, dll).
- `db:seed` mengisi data awal: kategori dasar, 1 akun admin, contoh cabang & testimoni.
- `storage:link` wajib supaya gambar produk/artikel/banner yang diupload lewat CMS bisa tampil di halaman publik.

## 9. Jalankan Aplikasi

**Cara termudah (direkomendasikan untuk development):**
```
php artisan serve
```
Buka `http://127.0.0.1:8000` di browser.

**Kalau mau lewat Apache XAMPP (folder di `htdocs`):**
Buka `http://localhost/tigalapankaos/public`. Pastikan `mod_rewrite` aktif di Apache (default XAMPP sudah aktif) supaya URL bersih (`/produk`, bukan `/index.php/produk`) berfungsi lewat file `public/.htaccess` bawaan Laravel.

## 10. Login ke CMS

Buka `/login` (contoh: `http://127.0.0.1:8000/login`), masuk dengan:
- **Email**: `admin@tigalapankaos.co.id`
- **Password**: `ubah-password-ini`

**Segera ganti password ini setelah login pertama** (lewat menu Pengguna, karena akun ini role Admin).

## 11. Cek Cepat Semua Berjalan

- Beranda publik (`/`) tampil dengan data dari database (produk, banner, dsb — kalau masih kosong, tambahkan lewat CMS).
- `/login` bisa diakses (tampilannya sudah bergaya, bukan HTML polos — tandanya `npm run build` di Langkah 3 berhasil) dan berhasil masuk ke Dashboard.
- Dashboard menampilkan stat card (Pesanan, Leads, Stok, dst) dan card "Traffic Website" berstatus "Belum Terhubung" (normal, ini baru aktif kalau GA4 sudah disetup — lihat `GOOGLE-ANALYTICS-SETUP.md`).
- Upload gambar produk baru dari menu Produk, cek gambarnya muncul di halaman publik (memverifikasi `storage:link` berhasil).

## Troubleshooting Umum

| Gejala | Kemungkinan Sebab | Solusi |
|---|---|---|
| `require(.../routes/auth.php): Failed to open stream` | Laravel Breeze belum diinstall (Langkah 3 terlewat) | Jalankan `composer require laravel/breeze --dev` lalu `php artisan breeze:install blade` sebelum menyalin file dari `laravel-cms` |
| Halaman login error "Vite manifest not found" | `npm run build` belum dijalankan setelah install Breeze | Jalankan `npm install && npm run build` di folder project |
| `SQLSTATE[HY000] [2002]` saat migrate | MySQL XAMPP belum jalan, atau port/host salah di `.env` | Pastikan MySQL "Running" di XAMPP Control Panel, cek `DB_HOST`/`DB_PORT` |
| Halaman blank / error 500 | Folder `storage` & `bootstrap/cache` tidak writable | `chmod -R 775 storage bootstrap/cache` (Mac/Linux) atau cek permission folder di Windows |
| Error 419 "Page Expired" saat login/submit form | `APP_URL` di `.env` tidak sesuai dengan URL yang dipakai browser | Samakan `APP_URL` dengan alamat yang diketik di browser |
| Gambar produk tidak muncul | Lupa jalankan `php artisan storage:link` | Jalankan perintahnya, lalu refresh |
| `Class "Google\Analytics\..." not found` | Wajar — package GA4 memang belum diinstall (opsional) | Ikuti `GOOGLE-ANALYTICS-SETUP.md` kalau mau aktifkan, kalau tidak, abaikan (CMS tetap jalan normal) |
| `Class "App\Http\Controllers\Controller" not found` | File dasar `app/Http/Controllers/Controller.php` hilang/tertimpa saat proses copy-paste folder `app` | File ini sudah disertakan di `laravel-cms/app/Http/Controllers/Controller.php` — pastikan ikut tersalin, lalu jalankan `composer dump-autoload` |
| Composer lambat/gagal saat `create-project` | Koneksi internet/proxy | Coba ulang, atau pakai `composer create-project laravel/laravel tigalapankaos --prefer-dist` |
