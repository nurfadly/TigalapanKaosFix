<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockItem extends Model
{
    protected $fillable = [
        'stock_catalog_id',
        'outlet',
        'beginning',
        'purchase_order',
        'sales',
        'transfer',
        'adjustment',
        'ending',
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(StockCatalog::class, 'stock_catalog_id');
    }
}
