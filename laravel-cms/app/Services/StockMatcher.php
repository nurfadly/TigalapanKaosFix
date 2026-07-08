<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Heuristik pencocokan nama item stok dari POS (mis. "ABU MISTY 30S PDK 2XL")
 * ke Produk katalog website (mis. "Kaos Combed 30s Premium").
 *
 * Formatnya sangat berbeda, jadi ini best-effort: cari kemiripan kata kunci
 * gramasi (20S/24S/30S) dan potongan/kategori, lalu skor kemiripan teks.
 * Item dengan skor rendah tetap disimpan sebagai "belum cocok" supaya
 * admin bisa cocokkan manual lewat halaman Pencocokan Stok.
 */
class StockMatcher
{
    private const THRESHOLD = 0.42;

    private Collection $products;

    public function __construct()
    {
        $this->products = Product::query()->select(['id', 'name', 'description'])->get();
    }

    /**
     * @return array{product_id: int|null, score: float|null}
     */
    public function match(string $rawName, ?string $category): array
    {
        if ($this->products->isEmpty()) {
            return ['product_id' => null, 'score' => null];
        }

        $needle = $this->normalize($rawName.' '.$category);

        $best = null;
        $bestScore = 0.0;

        foreach ($this->products as $product) {
            $haystack = $this->normalize($product->name.' '.$product->description);
            $score = $this->similarity($needle, $haystack);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $product;
            }
        }

        if ($best && $bestScore >= self::THRESHOLD) {
            return ['product_id' => $best->id, 'score' => round($bestScore, 2)];
        }

        return ['product_id' => null, 'score' => $best ? round($bestScore, 2) : null];
    }

    private function normalize(string $text): string
    {
        $text = strtolower($text);
        $text = str_replace(['pdk', 'pjg'], ['pendek', 'panjang'], $text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);

        return trim(preg_replace('/\s+/', ' ', $text));
    }

    /**
     * Skor 0..1 berdasarkan proporsi kata (token) yang sama antara dua teks,
     * ditambah bonus similar_text() untuk menangkap kemiripan substring.
     */
    private function similarity(string $a, string $b): float
    {
        $tokensA = array_unique(array_filter(explode(' ', $a), fn ($t) => strlen($t) >= 3));
        $tokensB = array_unique(array_filter(explode(' ', $b), fn ($t) => strlen($t) >= 3));

        if (empty($tokensA) || empty($tokensB)) {
            return 0.0;
        }

        $overlap = count(array_intersect($tokensA, $tokensB));
        $tokenScore = $overlap / max(count($tokensA), 1);

        similar_text($a, $b, $percentTextScore);
        $textScore = $percentTextScore / 100;

        return ($tokenScore * 0.7) + ($textScore * 0.3);
    }
}
