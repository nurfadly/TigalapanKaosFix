<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
            'onSaleCount' => Product::whereNotNull('discount_price')->count(),
            'articleCount' => Article::count(),
            'publishedArticleCount' => Article::published()->count(),
            'categoryCount' => Category::count(),
            'latestProducts' => Product::latest()->take(5)->get(),
            'latestArticles' => Article::latest()->take(5)->get(),
        ]);
    }
}
