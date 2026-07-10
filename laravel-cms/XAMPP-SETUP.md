# Panduan Setup CMS Tigalapankaos di XAMPP (Lokal)

Project yang dikirim berisi **kode aplikasi Laravel** (`app/`, `database/`, `resources/`, `routes/`, `public/cart.js`) — bukan instalasi Laravel lengkap. Jadi langkah pertama adalah bikin skeleton Laravel kosong lewat Composer, baru file-file ini ditimpakan/digabung ke dalamnya. Tidak perlu Node/npm — CSS pakai Tailwind lewat CDN, jadi tidak ada proses build frontend.

## 0. Yang Perlu Disiapkan

- **XAMPP** dengan PHP **8.1 atau 8.2** (unduh versi XAMPP yang sesuai di apachefriends.org — pilih installer yang menyertakan PHP 8.1.x/8.2.x, bukan yang PHP 8.0 ke bawah).
- **Composer** (unduh di getcomposer.org, install versi Windows/Mac/Linux sesuai OS).
- Text editor apa saja (VS Code, dsb) — opsional, hanya untuk mengecek `.env`.

Cek PHP & Composer sudah kebaca di terminal:
```
php -v
composer -v
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

## 3. Gabungkan File yang Sudah Dikirim

Dari folder `laravel-cms` yang dikirim, salin/timpakan ke folder `tigalapankaos` hasil Langkah 2:

| Dari (`laravel-cms/...`)            | Ke (`tigalapankaos/...`)             | Catatan |
|---|---|---|
| `app/Http/Controllers/*`            | `app/Http/Controllers/`              | Timpa/tambah semua |
| `app/Models/*`                      | `app/Models/`                        | Timpa/tambah semua |
| `app/Services/*`                    | `app/Services/`                      | Buat folder `Services` baru |
| `app/Providers/AppServiceProvider.php` | `app/Providers/AppServiceProvider.php` | **Timpa** file bawaan Laravel |
| `database/migrations/*`             | `database/migrations/`               | Tambahkan semua file migration |
| `database/seeders/*`                | `database/seeders/`                  | **Timpa** `DatabaseSeeder.php` bawaan, tambahkan seeder lain |
| `resources/views/*`                 | `resources/views/`                   | Timpa/tambah semua (termasuk folder `components`, `site`, `settings`, dll) |
| `routes/web.php`                    | `routes/web.php`                     | **Timpa** file bawaan |
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

## 4. Pindahkan Project ke `htdocs` (Opsional, lihat Langkah 7 dulu)

Kalau nanti mau akses lewat `http://localhost/...`, folder `tigalapankaos` perlu dipindah/disalin ke dalam `C:\xampp\htdocs\` (Windows) atau `/Applications/XAMPP/htdocs/` (Mac). Kalau memilih pakai `php artisan serve` (lebih simpel, direkomendasikan untuk development), lewati langkah ini — cukup taruh folder di mana saja.

## 5. Buat Database di phpMyAdmin

1. Buka browser ke `http://localhost/phpmyadmin`.
2. Klik **New**, buat database baru dengan nama `tigalapankaos`, collation `utf8mb4_unicode_ci`.

## 6. Konfigurasi `.env`

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

## 7. Install Dependency & Siapkan Aplikasi

Dari dalam folder `tigalapankaos`:
```
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```
- `migrate` membuat semua tabel (produk, kategori, artikel, pesanan, stok, leads, testimoni, cabang, settings, dll).
- `db:seed` mengisi data awal: kategori dasar, 1 akun admin, contoh cabang & testimoni.
- `storage:link` wajib supaya gambar produk/artikel/banner yang diupload lewat CMS bisa tampil di halaman publik.

## 8. Jalankan Aplikasi

**Cara termudah (direkomendasikan untuk development):**
```
php artisan serve
```
Buka `http://127.0.0.1:8000` di browser.

**Kalau mau lewat Apache XAMPP (folder di `htdocs`):**
Buka `http://localhost/tigalapankaos/public`. Pastikan `mod_rewrite` aktif di Apache (default XAMPP sudah aktif) supaya URL bersih (`/produk`, bukan `/index.php/produk`) berfungsi lewat file `public/.htaccess` bawaan Laravel.

## 9. Login ke CMS

Buka `/login` (contoh: `http://127.0.0.1:8000/login`), masuk dengan:
- **Email**: `admin@tigalapankaos.co.id`
- **Password**: `ubah-password-ini`

**Segera ganti password ini setelah login pertama** (lewat menu Pengguna, karena akun ini role Admin).

## 10. Cek Cepat Semua Berjalan

- Beranda publik (`/`) tampil dengan data dari database (produk, banner, dsb — kalau masih kosong, tambahkan lewat CMS).
- `/login` bisa diakses dan berhasil masuk ke Dashboard.
- Dashboard menampilkan stat card (Pesanan, Leads, Stok, dst) dan card "Traffic Website" berstatus "Belum Terhubung" (normal, ini baru aktif kalau GA4 sudah disetup — lihat `GOOGLE-ANALYTICS-SETUP.md`).
- Upload gambar produk baru dari menu Produk, cek gambarnya muncul di halaman publik (memverifikasi `storage:link` berhasil).

## Troubleshooting Umum

| Gejala | Kemungkinan Sebab | Solusi |
|---|---|---|
| `SQLSTATE[HY000] [2002]` saat migrate | MySQL XAMPP belum jalan, atau port/host salah di `.env` | Pastikan MySQL "Running" di XAMPP Control Panel, cek `DB_HOST`/`DB_PORT` |
| Halaman blank / error 500 | Folder `storage` & `bootstrap/cache` tidak writable | `chmod -R 775 storage bootstrap/cache` (Mac/Linux) atau cek permission folder di Windows |
| Error 419 "Page Expired" saat login/submit form | `APP_URL` di `.env` tidak sesuai dengan URL yang dipakai browser | Samakan `APP_URL` dengan alamat yang diketik di browser |
| Gambar produk tidak muncul | Lupa jalankan `php artisan storage:link` | Jalankan perintahnya, lalu refresh |
| `Class "Google\Analytics\..." not found` | Wajar — package GA4 memang belum diinstall (opsional) | Ikuti `GOOGLE-ANALYTICS-SETUP.md` kalau mau aktifkan, kalau tidak, abaikan (CMS tetap jalan normal) |
| Composer lambat/gagal saat `create-project` | Koneksi internet/proxy | Coba ulang, atau pakai `composer create-project laravel/laravel tigalapankaos --prefer-dist` |
