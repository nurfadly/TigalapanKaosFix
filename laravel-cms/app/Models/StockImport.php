<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockImport extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'filename',
        'total_rows',
        'total_outlets',
        'total_items',
        'total_matched',
        'imported_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function importer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }
}
