<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'whatsapp_number',
        'contact_email',
        'instagram_url',
        'tiktok_url',
        'address_city',
        'address_province',
        'footer_description',
        'ga4_property_id',
        'ga4_credentials_path',
    ];

    /**
     * Cache request-lifecycle sederhana supaya Setting::current() aman
     * dipanggil berkali-kali (di layout, footer, tiap halaman) tanpa
     * query berulang ke database.
     */
    private static ?self $cached = null;

    /**
     * Ambil satu-satunya baris settings, atau buat baris default kalau belum ada
     * (misalnya baru migrate tapi belum pernah dibuka halaman Pengaturan di CMS).
     */
    public static function current(): self
    {
        if (self::$cached !== null) {
            return self::$cached;
        }

        self::$cached = self::query()->first() ?? self::create([
            'site_name' => 'tigalapankaos',
            'whatsapp_number' => '6280000000000',
            'contact_email' => 'halo@tigalapankaos.co.id',
            'address_city' => 'Makassar',
            'address_province' => 'Sulawesi Selatan',
            'footer_description' => 'Supplier kaos polos cotton combed untuk mitra usaha, konveksi, dan komunitas sejak 2018.',
        ]);

        return self::$cached;
    }

    public function getWhatsappLinkAttribute(): string
    {
        return 'https://wa.me/'.preg_replace('/[^0-9]/', '', $this->whatsapp_number);
    }

    /**
     * True kalau Property ID GA4 dan file credentials service account sudah diisi.
     * Dipakai GoogleAnalyticsService & Dashboard untuk memutuskan tampilkan data
     * asli atau placeholder "belum terhubung".
     */
    public function isGa4Configured(): bool
    {
        return filled($this->ga4_property_id) && filled($this->ga4_credentials_path);
    }
}
