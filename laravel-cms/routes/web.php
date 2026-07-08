<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CATATAN PENGGABUNGAN
|--------------------------------------------------------------------------
| File ini menggantikan routes/web.php bawaan Laravel Breeze.
| Kalau kamu sudah install Breeze duluan, cukup timpa isinya dengan file ini
| (baris require __DIR__.'/auth.php' dan route /profile tetap dipertahankan
| supaya login/register/logout bawaan Breeze tetap jalan).
|
| Sejak Fase 3c, "/" sudah jadi halaman publik (Beranda), bukan lagi
| redirect ke dashboard admin. Admin login tetap di /login, dan setelah
| login akan diarahkan ke /dashboard seperti biasa.
*/

// ==================== HALAMAN PUBLIK ====================
// Dibaca langsung dari database (Produk, Artikel, Banner, Kategori),
// menggantikan data hardcoded yang dulu ada di index.html, produk.html, dst.

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/produk', [SiteController::class, 'products'])->name('site.products');
Route::get('/produk/{product}', [SiteController::class, 'productDetail'])->name('site.product-detail');
Route::get('/tentang', [SiteController::class, 'about'])->name('site.about');
Route::get('/artikel', [SiteController::class, 'articles'])->name('site.articles');
Route::get('/artikel/{article}', [SiteController::class, 'articleDetail'])->name('site.article-detail');
Route::get('/kontak', [SiteController::class, 'contact'])->name('site.contact');

// Dipanggil lewat fetch() dari cart.js saat customer checkout.
Route::post('/pesanan', [SiteController::class, 'storeOrder'])->name('site.order.store');

// Submit form di halaman Kontak — tersimpan sebagai Lead di CMS.
Route::post('/kontak', [SiteController::class, 'storeLead'])->name('site.lead.store');

// ==================== ADMIN / CMS ====================

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CMS: Produk — nama, kategori, harga, diskon, warna, ukuran, gambar
    // Bisa diakses Admin maupun Editor.
    Route::resource('products', ProductController::class)->except(['show']);

    // CMS: Artikel — bisa diakses Admin maupun Editor.
    Route::resource('articles', ArticleController::class)->except(['show']);

    // CMS: Banner / hero slider di beranda — bisa diakses Admin maupun Editor.
    Route::resource('banners', BannerController::class)->except(['show']);

    // CMS: Pesanan yang masuk dari checkout website.
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update', 'destroy']);

    // CMS: Stok Outlet — upload file POS, browse data, pencocokan ke Produk.
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('/stock/data', [StockController::class, 'data'])->name('stock.data');
    Route::get('/stock/match', [StockController::class, 'matchIndex'])->name('stock.match');
    Route::post('/stock/match/{stockCatalog}', [StockController::class, 'matchUpdate'])->name('stock.match.update');

    // CMS: Cabang — ditampilkan di halaman Tentang & Kontak.
    Route::resource('branches', BranchController::class)->except(['show']);

    // CMS: Testimoni — ditampilkan di beranda.
    Route::resource('testimonials', TestimonialController::class)->except(['show']);

    // CMS: Leads dari form Hubungi Kami.
    Route::resource('leads', LeadController::class)->only(['index', 'show', 'update', 'destroy']);
});

// Rute khusus Admin: Kategori & kelola akun pengguna.
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
});

require __DIR__.'/auth.php';
