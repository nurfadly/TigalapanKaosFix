<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * CATATAN PENGGABUNGAN:
     * File ini menggantikan app/Providers/AppServiceProvider.php bawaan Laravel.
     * Kalau kamu sudah punya isi lain di register()/boot(), gabungkan baris
     * View::composer(...) di bawah ke boot() kamu yang sudah ada.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Bikin variabel $settings otomatis tersedia di semua halaman publik
        // (site.*) dan layout-nya, supaya nomor WA/email/sosmed cukup diubah
        // sekali lewat menu Pengaturan di CMS, tanpa sentuh kode Blade.
        View::composer(['site.*', 'components.site-layout'], function ($view) {
            $view->with('settings', Setting::current());
        });
    }
}
