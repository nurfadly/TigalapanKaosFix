<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Kategori awal, mengikuti tab filter yang sudah ada di produk.html dan artikel.html.
     */
    public function run(): void
    {
        $productCategories = ['Pria', 'Wanita', 'Anak', 'Polo'];
        foreach ($productCategories as $name) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name), 'type' => 'product'],
                ['name' => $name]
            );
        }

        $articleCategories = ['Bahan', 'Tren', 'Bisnis'];
        foreach ($articleCategories as $name) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name), 'type' => 'article'],
                ['name' => $name]
            );
        }
    }
}
