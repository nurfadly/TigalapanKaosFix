<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Bikin 1 akun admin buat login ke CMS.
     * PENTING: ganti password ini setelah login pertama kali.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@tigalapankaos.co.id'],
            [
                'name' => 'Admin Tigalapankaos',
                'role' => 'admin',
                'password' => Hash::make('ubah-password-ini'),
                'email_verified_at' => now(),
            ]
        );
    }
}
