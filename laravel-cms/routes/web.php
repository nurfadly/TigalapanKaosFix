<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CATATAN PENGGABUNGAN
|--------------------------------------------------------------------------
| File ini menggantikan routes/web.php bawaan Laravel Breeze.
| Kalau kamu sudah install Breeze duluan, cukup timpa isinya dengan file ini
| (baris require __DIR__.'/auth.php' dan route /profile tetap dipertahankan
| supaya login/register/logout bawaan Breeze tetap jalan).
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CMS: Kategori (dipakai bersama Produk & Artikel lewat kolom `type`)
    Route::resource('categories', CategoryController::class)->except(['show']);

    // CMS: Produk — nama, kategori, harga, diskon, warna, ukuran, gambar
    Route::resource('products', ProductController::class)->except(['show']);

    // CMS: Artikel
    Route::resource('articles', ArticleController::class)->except(['show']);
});

require __DIR__.'/auth.php';
