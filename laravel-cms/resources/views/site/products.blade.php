<x-site-layout title="Produk - Tigalapankaos" description="Katalog kaos polos cotton combed Tigalapankaos untuk pria, wanita, dan anak.">

<main class="pt-16 md:pt-[72px]">

  <!-- PAGE HEADER -->
  <section class="py-12 md:py-16 border-b border-onyx/10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <p class="text-sm text-onyx/50"><a href="{{ route('home') }}" class="hover:text-onyx">Beranda</a> / Produk</p>
      <h1 class="mt-3 text-3xl md:text-5xl font-black tracking-tight">Katalog Produk</h1>
      <p class="mt-3 text-onyx/70 max-w-[55ch]">Kaos polos cotton combed 20s, 24s, dan 30s dalam lebih dari 20 pilihan warna, siap kirim ke seluruh Indonesia Timur.</p>
    </div>
  </section>

  <!-- FILTER TABS -->
  <section class="py-6 border-b border-onyx/10 sticky top-16 md:top-[72px] bg-cloud/95 backdrop-blur z-30">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8 flex gap-3 overflow-x-auto">
      <a href="{{ route('site.products') }}" class="tab {{ request('kategori') ? '' : 'active' }} shrink-0 px-5 py-2 rounded-full text-sm font-bold border border-onyx/15">Semua</a>
      @foreach ($categories as $cat)
        <a href="{{ route('site.products', ['kategori' => $cat->slug]) }}" class="tab {{ request('kategori') === $cat->slug ? 'active' : '' }} shrink-0 px-5 py-2 rounded-full text-sm font-bold border border-onyx/15">{{ $cat->name }}</a>
      @endforeach
    </div>
  </section>

  <!-- GRID -->
  <section class="py-12 md:py-16">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
        @foreach ($products as $product)
          @php
            $detailUrl = route('site.product-detail', $product);
            $firstColor = $product->colors->first();
          @endphp
          <div class="product-card group bg-white overflow-hidden">
            <div class="relative">
              <a href="{{ $detailUrl }}" class="block overflow-hidden bg-onyx">
                @if ($product->primary_image_url)
                  <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover opacity-90 group-hover:scale-[1.04] transition-transform duration-500" />
                @else
                  <div class="w-full aspect-square bg-onyx/10"></div>
                @endif
              </a>
              @if ($product->is_on_sale)
                <div class="absolute top-3 left-3 bg-gusto text-onyx text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 pointer-events-none">Diskon</div>
              @endif
            </div>
            <div class="p-4 border-t border-onyx/10 flex items-center justify-between gap-2">
              <a href="{{ $detailUrl }}" class="min-w-0">
                <p class="font-bold text-sm truncate">{{ $product->name }}</p>
                <p class="text-sm mt-0.5">
                  @if ($product->is_on_sale)
                    <span class="font-bold text-gusto">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</span>
                    <span class="text-onyx/35 line-through text-xs">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                  @else
                    <span class="text-onyx/60">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                  @endif
                </p>
                @if ($firstColor) <p class="text-xs text-onyx/60">{{ $firstColor->name }}</p> @endif
                @if ($product->colors->isNotEmpty())
                  <div class="mt-1.5 flex items-center gap-1" aria-hidden="true">
                    @foreach ($product->colors as $color)
                      <span class="w-3.5 h-3.5 rounded-full ring-1 ring-offset-1 ring-onyx" style="background:{{ $color->hex }}"></span>
                    @endforeach
                  </div>
                  <p class="sr-only">Tersedia warna: {{ $product->colors->pluck('name')->implode(', ') }}</p>
                @endif
                @if ($product->is_on_sale && $product->discount_end)
                  <p class="text-[10px] font-bold uppercase tracking-wide text-gusto mt-1">Promo s.d. {{ $product->discount_end->translatedFormat('d M') }}</p>
                @endif
              </a>
              <button class="add-to-cart shrink-0 w-9 h-9 rounded-full bg-onyx text-cloud flex items-center justify-center hover:bg-gusto hover:text-onyx transition-colors" aria-label="Tambah ke keranjang"
                data-id="{{ $product->slug }}" data-name="{{ $product->name }}" data-price="{{ $product->final_price }}" data-variant="{{ $firstColor->name ?? '' }}" data-image="{{ $product->primary_image_url }}"><i class="ph-bold ph-plus"></i></button>
            </div>
          </div>
        @endforeach
      </div>
      @if ($products->isEmpty())
        <p class="text-center text-onyx/50 py-16">Belum ada produk untuk kategori ini.</p>
      @endif
    </div>
  </section>

  <!-- CTA BANNER -->
  <section>
    <div class="bg-gusto">
      <div class="max-w-[1400px] mx-auto px-5 md:px-8 py-14 md:py-16 flex flex-col md:flex-row items-center justify-between gap-6 text-onyx">
        <div>
          <h2 class="text-2xl md:text-3xl font-black max-w-[20ch]">Butuh Warna atau Ukuran Tertentu?</h2>
          <p class="mt-2 text-onyx/80 max-w-[48ch]">Admin kami siap bantu cek ketersediaan stok secara langsung.</p>
        </div>
        <a href="https://wa.me/6280000000000" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-onyx text-cloud font-bold px-7 py-3.5 rounded-full hover:opacity-85 active:scale-[0.98] transition-all shrink-0">
          <i class="ph-bold ph-whatsapp-logo"></i> Hubungi Kami
        </a>
      </div>
    </div>
  </section>

</main>

</x-site-layout>
