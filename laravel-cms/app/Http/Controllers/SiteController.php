<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Controller untuk halaman publik (Beranda, Produk, Artikel, Tentang, Kontak).
 * Semua konten diambil dari database lewat CMS, menggantikan data hardcoded
 * yang dulu ada langsung di file HTML (index.html, produk.html, dst).
 */
class SiteController extends Controller
{
    public function home(): View
    {
        $banners = Banner::visible()->get();

        $featuredProducts = Product::with(['colors', 'images'])
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        $latestArticles = Article::published()->latest('published_at')->take(3)->get();

        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();

        return view('site.home', compact('banners', 'featuredProducts', 'latestArticles', 'testimonials'));
    }

    public function products(Request $request): View
    {
        $products = Product::with(['colors', 'images', 'category'])
            ->where('is_active', true)
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->kategori)))
            ->latest()
            ->get();

        $categories = Category::where('type', 'product')->orderBy('name')->get();

        return view('site.products', compact('products', 'categories'));
    }

    public function productDetail(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['colors', 'sizes', 'images' => fn ($q) => $q->orderBy('sort_order')]);

        $related = Product::with(['images'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('site.product-detail', compact('product', 'related'));
    }

    public function about(): View
    {
        $branches = Branch::orderBy('sort_order')->get();

        return view('site.about', compact('branches'));
    }

    public function articles(Request $request): View
    {
        $articles = Article::with('category')
            ->published()
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->kategori)))
            ->latest('published_at')
            ->get();

        $categories = Category::where('type', 'article')->orderBy('name')->get();

        return view('site.articles', compact('articles', 'categories'));
    }

    public function articleDetail(Article $article): View
    {
        abort_unless($article->published_at && $article->published_at->lte(now()), 404);

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($q) => $q->where('category_id', $article->category_id))
            ->take(3)
            ->get();

        return view('site.article-detail', compact('article', 'related'));
    }

    public function contact(): View
    {
        $branches = Branch::orderBy('sort_order')->get();

        return view('site.contact', compact('branches'));
    }

    /**
     * Dipanggil dari cart.js saat customer menekan "Pesan Sekarang" di modal checkout.
     * Pesanan disimpan ke database supaya muncul di CMS, lalu customer tetap
     * diarahkan ke WhatsApp untuk konfirmasi seperti alur sebelumnya.
     */
    public function storeOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'string'],
            'items.*.name' => ['required', 'string'],
            'items.*.variant' => ['nullable', 'string'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $total = 0;
            foreach ($data['items'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'total' => $total,
                'status' => 'new',
            ]);

            foreach ($data['items'] as $item) {
                // Item id dari cart.js adalah slug produk (lihat data-id di kartu produk).
                $product = Product::where('slug', $item['id'])->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product?->id,
                    'product_name' => $item['name'],
                    'variant' => $item['variant'] ?? null,
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
            }

            return $order;
        });

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }

    /**
     * Dipanggil dari form di halaman Kontak. Disimpan sebagai Lead
     * supaya admin bisa pantau & tindak lanjuti dari CMS.
     */
    public function storeLead(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'topic' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string'],
        ]);

        Lead::create($data);

        return response()->json(['success' => true]);
    }
}
