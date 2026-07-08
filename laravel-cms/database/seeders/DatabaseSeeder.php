<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ganti isi database/seeders/DatabaseSeeder.php bawaan Laravel dengan ini,
     * lalu jalankan: php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
