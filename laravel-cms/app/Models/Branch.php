<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'city',
        'province',
        'is_hq',
        'label',
        'sort_order',
    ];

    protected $casts = [
        'is_hq' => 'boolean',
    ];

    public function getDisplayLabelAttribute(): string
    {
        return $this->label ?: ($this->is_hq ? 'Kantor Pusat' : 'Cabang');
    }
}
