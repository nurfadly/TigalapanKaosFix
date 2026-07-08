<x-admin-layout title="Dashboard">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Total Produk</p>
            <p class="text-3xl font-black mt-1">{{ $productCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Produk Aktif</p>
            <p class="text-3xl font-black mt-1">{{ $activeProductCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Sedang Diskon</p>
            <p class="text-3xl font-black mt-1 text-gusto">{{ $onSaleCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Artikel Terbit</p>
            <p class="text-3xl font-black mt-1">{{ $publishedArticleCount }} / {{ $articleCount }}</p>
        </div>
    </div>

    <div class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <div class="flex items-center justify-between mb-4">
                <p class="font-bold">Produk Terbaru</p>
                <a href="{{ route('products.index') }}" class="text-sm text-gusto font-semibold">Lihat semua</a>
            </div>
            <ul class="divide-y divide-onyx/10 text-sm">
                @forelse ($latestProducts as $product)
                    <li class="py-2 flex items-center justify-between">
                        <span>{{ $product->name }}</span>
                        <span class="text-onyx/50">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    </li>
                @empty
                    <li class="py-2 text-onyx/50">Belum ada produk.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <div class="flex items-center justify-between mb-4">
                <p class="font-bold">Artikel Terbaru</p>
                <a href="{{ route('articles.index') }}" class="text-sm text-gusto font-semibold">Lihat semua</a>
            </div>
            <ul class="divide-y divide-onyx/10 text-sm">
                @forelse ($latestArticles as $article)
                    <li class="py-2 flex items-center justify-between">
                        <span>{{ $article->title }}</span>
                        <span class="text-onyx/50">{{ $article->published_at ? 'Terbit' : 'Draft' }}</span>
                    </li>
                @empty
                    <li class="py-2 text-onyx/50">Belum ada artikel.</li>
                @endforelse
            </ul>
        </div>
    </div>

</x-admin-layout>
