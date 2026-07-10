<x-admin-layout title="Dashboard">

    @php
        $needsAttention = $newOrderCount > 0 || $newLeadCount > 0 || $unmatchedStockCount > 0;
    @endphp

    @if ($needsAttention)
        <div class="mb-8 bg-onyx text-cloud rounded-xl p-5">
            <p class="font-bold flex items-center gap-2"><i class="ph-bold ph-bell-ringing text-gusto"></i> Butuh Perhatian</p>
            <div class="mt-3 grid sm:grid-cols-3 gap-3 text-sm">
                @if ($newOrderCount > 0)
                    <a href="{{ route('orders.index', ['status' => 'new']) }}" class="bg-white/10 hover:bg-white/20 transition-colors rounded-lg p-3">
                        <span class="font-bold text-gusto">{{ $newOrderCount }}</span> pesanan baru belum diproses
                    </a>
                @endif
                @if ($newLeadCount > 0)
                    <a href="{{ route('leads.index', ['status' => 'new']) }}" class="bg-white/10 hover:bg-white/20 transition-colors rounded-lg p-3">
                        <span class="font-bold text-gusto">{{ $newLeadCount }}</span> lead baru belum dihubungi
                    </a>
                @endif
                @if ($unmatchedStockCount > 0)
                    <a href="{{ route('stock.match') }}" class="bg-white/10 hover:bg-white/20 transition-colors rounded-lg p-3">
                        <span class="font-bold text-gusto">{{ $unmatchedStockCount }}</span> item stok belum tertaut produk
                    </a>
                @endif
            </div>
        </div>
    @endif

    <p class="text-xs font-bold uppercase text-onyx/40 mb-2">Penjualan</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Pesanan Baru</p>
            <p class="text-3xl font-black mt-1 {{ $newOrderCount > 0 ? 'text-gusto' : '' }}">{{ $newOrderCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Sedang Diproses</p>
            <p class="text-3xl font-black mt-1">{{ $processingOrderCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Omzet Bulan Ini</p>
            <p class="text-2xl font-black mt-1">Rp{{ number_format($revenueThisMonth, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Lead Baru</p>
            <p class="text-3xl font-black mt-1 {{ $newLeadCount > 0 ? 'text-gusto' : '' }}">{{ $newLeadCount }}</p>
        </div>
    </div>

    <p class="text-xs font-bold uppercase text-onyx/40 mb-2 mt-8">Katalog & Konten</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Total Produk</p>
            <p class="text-3xl font-black mt-1">{{ $productCount }}</p>
            <p class="text-xs text-onyx/40 mt-1">{{ $activeProductCount }} aktif &middot; {{ $onSaleCount }} diskon</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Artikel Terbit</p>
            <p class="text-3xl font-black mt-1">{{ $publishedArticleCount }} / {{ $articleCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Banner Aktif</p>
            <p class="text-3xl font-black mt-1">{{ $activeBannerCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Testimoni Aktif</p>
            <p class="text-3xl font-black mt-1">{{ $activeTestimonialCount }}</p>
        </div>
    </div>

    <p class="text-xs font-bold uppercase text-onyx/40 mb-2 mt-8">Stok & Cabang</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Item Stok</p>
            <p class="text-3xl font-black mt-1">{{ $latestStockImport?->total_items ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Belum Tertaut</p>
            <p class="text-3xl font-black mt-1 {{ $unmatchedStockCount > 0 ? 'text-gusto' : '' }}">{{ $unmatchedStockCount }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Update Stok Terakhir</p>
            <p class="text-sm font-bold mt-2">{{ $latestStockImport?->created_at?->translatedFormat('d M Y, H:i') ?? 'Belum ada' }}</p>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <p class="text-xs font-bold text-onyx/50 uppercase">Cabang</p>
            <p class="text-3xl font-black mt-1">{{ $branchCount }}</p>
        </div>
    </div>

    <p class="text-xs font-bold uppercase text-onyx/40 mb-2 mt-8">Traffic Website</p>
    @if ($analyticsConnected && $trafficSummary)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-onyx/10">
                <p class="text-xs font-bold text-onyx/50 uppercase">Pengunjung (7 Hari)</p>
                <p class="text-3xl font-black mt-1">{{ number_format($trafficSummary['visitors'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-onyx/10">
                <p class="text-xs font-bold text-onyx/50 uppercase">Sesi (7 Hari)</p>
                <p class="text-3xl font-black mt-1">{{ number_format($trafficSummary['sessions'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-onyx/10 md:col-span-2">
                <p class="text-xs font-bold text-onyx/50 uppercase">Halaman Terpopuler</p>
                <ul class="mt-2 text-sm divide-y divide-onyx/10">
                    @forelse ($topPages ?? [] as $page)
                        <li class="py-1.5 flex items-center justify-between">
                            <span class="truncate">{{ $page['path'] }}</span>
                            <span class="text-onyx/50 shrink-0 ml-2">{{ number_format($page['views'], 0, ',', '.') }}</span>
                        </li>
                    @empty
                        <li class="py-1.5 text-onyx/50">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @else
        <div class="bg-white p-5 rounded-xl border border-onyx/10 border-dashed">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-onyx/5 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-chart-line text-lg text-onyx/40"></i>
                </div>
                <div>
                    <p class="font-bold text-sm">Belum Terhubung ke Google Analytics</p>
                    <p class="text-xs text-onyx/50 mt-0.5">
                        Isi GA4 Property ID dan credentials di
                        <a href="{{ route('settings.edit') }}" class="text-gusto font-semibold hover:underline">menu Pengaturan</a>
                        saat website sudah live untuk menampilkan data pengunjung di sini.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <div class="flex items-center justify-between mb-4">
                <p class="font-bold">Pesanan Terbaru</p>
                <a href="{{ route('orders.index') }}" class="text-sm text-gusto font-semibold">Lihat semua</a>
            </div>
            <ul class="divide-y divide-onyx/10 text-sm">
                @forelse ($latestOrders as $order)
                    <li class="py-2 flex items-center justify-between">
                        <a href="{{ route('orders.show', $order) }}" class="hover:text-gusto">{{ $order->customer_name }}</a>
                        <span class="text-onyx/50">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                    </li>
                @empty
                    <li class="py-2 text-onyx/50">Belum ada pesanan.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white p-5 rounded-xl border border-onyx/10">
            <div class="flex items-center justify-between mb-4">
                <p class="font-bold">Leads Terbaru</p>
                <a href="{{ route('leads.index') }}" class="text-sm text-gusto font-semibold">Lihat semua</a>
            </div>
            <ul class="divide-y divide-onyx/10 text-sm">
                @forelse ($latestLeads as $lead)
                    <li class="py-2 flex items-center justify-between">
                        <a href="{{ route('leads.show', $lead) }}" class="hover:text-gusto">{{ $lead->name }}</a>
                        <span class="text-onyx/50">{{ $lead->topic }}</span>
                    </li>
                @empty
                    <li class="py-2 text-onyx/50">Belum ada lead.</li>
                @endforelse
            </ul>
        </div>
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
