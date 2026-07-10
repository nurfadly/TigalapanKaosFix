<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockCatalog;
use App\Models\StockImport;
use App\Models\Testimonial;
use App\Services\GoogleAnalyticsService;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $latestStockImport = StockImport::latest('created_at')->first();

        // Traffic website (Google Analytics 4). Balikin null selama belum
        // dikonfigurasi di menu Pengaturan — view menampilkan placeholder.
        $analytics = new GoogleAnalyticsService();
        $trafficSummary = $analytics->getSummary(7);
        $topPages = $analytics->getTopPages(7, 5);
        $analyticsConnected = $analytics->isAvailable();

        return view('dashboard', [
            // Traffic Website
            'analyticsConnected' => $analyticsConnected,
            'trafficSummary' => $trafficSummary,
            'topPages' => $topPages,
            // Produk & Artikel
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
            'onSaleCount' => Product::whereNotNull('discount_price')->count(),
            'articleCount' => Article::count(),
            'publishedArticleCount' => Article::published()->count(),
            'categoryCount' => Category::count(),

            // Pesanan
            'newOrderCount' => Order::where('status', 'new')->count(),
            'processingOrderCount' => Order::where('status', 'processing')->count(),
            'revenueThisMonth' => Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),

            // Leads
            'newLeadCount' => Lead::where('status', 'new')->count(),

            // Stok
            'latestStockImport' => $latestStockImport,
            'unmatchedStockCount' => StockCatalog::whereNull('matched_product_id')->count(),

            // Konten lain
            'activeBannerCount' => Banner::where('is_active', true)->count(),
            'activeTestimonialCount' => Testimonial::where('is_active', true)->count(),
            'branchCount' => Branch::count(),

            // Tabel ringkas
            'latestProducts' => Product::latest()->take(5)->get(),
            'latestArticles' => Article::latest()->take(5)->get(),
            'latestOrders' => Order::latest()->take(5)->get(),
            'latestLeads' => Lead::latest()->take(5)->get(),
        ]);
    }
}
