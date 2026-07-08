<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockCatalog extends Model
{
    protected $table = 'stock_catalog';

    protected $fillable = [
        'raw_name',
        'category',
        'matched_product_id',
        'match_score',
        'matched_manually',
    ];

    protected $casts = [
        'matched_manually' => 'boolean',
        'match_score' => 'float',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'matched_product_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockItem::class);
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->items->sum('ending');
    }
}
