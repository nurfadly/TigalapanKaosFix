<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * Daftar ukuran baku yang dipakai di halaman produk-detail.html (S/M/L/XL/2XL).
     * Dipakai admin panel sebagai pilihan checkbox, bukan input bebas,
     * supaya konsisten dengan tampilan di landing page.
     */
    public const SIZE_OPTIONS = ['S', 'M', 'L', 'XL', '2XL'];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'discount_start',
        'discount_end',
        'is_active',
    ];

    protected $casts = [
        'discount_start' => 'datetime',
        'discount_end' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Dipakai untuk route model binding di halaman publik: /produk/{product}
     * mengambil produk lewat slug, bukan id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * True kalau produk sedang dalam periode diskon aktif.
     * Dipakai frontend untuk tahu kapan harus tampilkan harga coret.
     */
    public function getIsOnSaleAttribute(): bool
    {
        if (!$this->discount_price) {
            return false;
        }

        $now = now();

        if ($this->discount_start && $now->lt($this->discount_start)) {
            return false;
        }

        if ($this->discount_end && $now->gt($this->discount_end)) {
            return false;
        }

        return true;
    }

    /**
     * Harga yang seharusnya ditagih ke pembeli saat ini,
     * sudah memperhitungkan diskon yang sedang berjalan.
     */
    public function getFinalPriceAttribute(): int
    {
        return $this->is_on_sale ? $this->discount_price : $this->price;
    }

    /**
     * URL gambar utama untuk kartu produk. Fallback ke gambar pertama
     * kalau belum ada yang ditandai utama, atau null kalau belum ada gambar sama sekali.
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        $image = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        return $image ? \Illuminate\Support\Facades\Storage::url($image->path) : null;
    }
}
