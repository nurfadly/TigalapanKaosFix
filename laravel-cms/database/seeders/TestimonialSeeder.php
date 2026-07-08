<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        Testimonial::firstOrCreate(
            ['author_name' => 'Andi Prasetyo'],
            [
                'quote' => 'Kualitas kaosnya konsisten dari pesanan pertama sampai sekarang. Stok juga selalu ada saat kami butuh cepat.',
                'author_title' => 'Pemilik Konveksi Anugerah Jaya, Makassar',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}
