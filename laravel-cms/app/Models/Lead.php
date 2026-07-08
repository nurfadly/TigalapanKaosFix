<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const STATUSES = [
        'new' => 'Baru',
        'contacted' => 'Sudah Dihubungi',
        'closed' => 'Selesai',
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'topic',
        'message',
        'status',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
