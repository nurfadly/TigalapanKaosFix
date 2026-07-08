<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * CATATAN PENGGABUNGAN:
 * File ini menggantikan app/Models/User.php bawaan Laravel/Breeze.
 * Kalau kamu sudah pernah mengubah file itu, cukup tambahkan bagian
 * yang ditandai "TAMBAHAN ROLE" di bawah ke file User.php kamu yang sudah ada.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // TAMBAHAN ROLE
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== TAMBAHAN ROLE ====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }
}
