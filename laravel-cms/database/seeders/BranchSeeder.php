<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['city' => 'Makassar', 'province' => 'Sulawesi Selatan', 'is_hq' => true, 'sort_order' => 1],
            ['city' => 'Samarinda', 'province' => 'Kalimantan Timur', 'is_hq' => false, 'sort_order' => 2],
            ['city' => 'Kendari', 'province' => 'Sulawesi Tenggara', 'is_hq' => false, 'sort_order' => 3],
            ['city' => 'Manado', 'province' => 'Sulawesi Utara', 'is_hq' => false, 'sort_order' => 4],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(['city' => $branch['city']], $branch);
        }
    }
}
