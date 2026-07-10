@php
    $images = $product->images->isNotEmpty() ? $product->images : collect();
    $mainImage = $images->firstWhere('is_primary', true) ?? $images->first();
    $mainImageUrl = $mainImage ? \Illuminate\Support\Facades\Storage::url($mainImage->path) : null;
@endphp
<x-site-layout title="{{ $product->name }} - Tigalapankaos" description="{{ $product->description ?? $product->name }}">

<main class="pt-16 md:pt-[72px]">

  <section class="py-6 border-b border-onyx/10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <p class="text-sm text-onyx/50">
        <a href="{{ route('home') }}" class="hover:text-onyx">Beranda</a> /
        <a href="{{ route('site.products') }}" class="hover:text-onyx">Produk</a> /
        {{ $product->name }}
      </p>
    </div>
  </section>

  <!-- PRODUCT -->
  <section class="py-10 md:py-14">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8 grid lg:grid-cols-2 gap-10 lg:gap-14">

      <!-- GALLERY -->
      <div>
        <div class="aspect-square overflow-hidden bg-onyx">
          @if ($mainImageUrl)
            <img id="mainImage" src="{{ $mainImageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
          @else
            <div class="w-full h-full"></div>
          @endif
        </div>
        @if ($images->count() > 1)
          <div class="mt-4 grid grid-cols-4 gap-3">
            @foreach ($images as $img)
              <button class="thumb aspect-square overflow-hidden border-2 {{ $loop->first ? 'border-onyx' : 'border-transparent hover:border-onyx/30' }}" data-img="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
              </button>
            @endforeach
          </div>
        @endif
      </div>

      <!-- DETAIL -->
      <div>
        <div class="flex items-start justify-between gap-4">
          <h1 class="text-3xl md:text-4xl font-black leading-tight">{{ $product->name }}</h1>
          <button id="wishlistBtn" aria-label="Simpan ke wishlist" aria-pressed="false" class="shrink-0 w-11 h-11 rounded-full border border-onyx/20 flex items-center justify-center hover:border-onyx transition-colors">
            <i class="ph-bold ph-heart text-lg"></i>
          </button>
        </div>

        <div class="mt-3 flex items-center gap-3">
          @if ($product->is_on_sale)
            <p class="text-2xl font-extrabold text-gusto">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</p>
            <p class="text-lg text-onyx/40 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
          @else
            <p class="text-2xl font-extrabold">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
          @endif
        </div>
        @if ($product->is_on_sale && ($product->discount_start || $product->discount_end))
          <p class="mt-1 text-sm font-bold text-gusto">
            Diskon terbatas, berlaku {{ $product->discount_start?->translatedFormat('d M Y') }} - {{ $product->discount_end?->translatedFormat('d M Y') }}
          </p>
        @endif

        @if ($product->description)
          <p class="mt-4 text-onyx/70 max-w-[52ch]">{{ $product->description }}</p>
        @endif

        @if ($product->colors->isNotEmpty())
          <div class="mt-8">
            <p class="text-sm font-bold">Warna: <span id="selectedColor" class="font-normal text-onyx/60">{{ $product->colors->first()->name }}</span></p>
            <div class="mt-3 flex items-center gap-3">
              @foreach ($product->colors as $color)
                <button class="swatch {{ $loop->first ? 'active' : '' }} w-9 h-9 rounded-full {{ strtoupper($color->hex) === '#FFFFFF' ? 'border border-onyx/20' : '' }}" style="background:{{ $color->hex }}" data-color="{{ $color->name }}" aria-label="Warna {{ $color->name }}"></button>
              @endforeach
            </div>
          </div>
        @endif

        @if ($product->sizes->isNotEmpty())
          <div class="mt-6">
            <p class="text-sm font-bold">Ukuran</p>
            <div class="mt-3 flex flex-wrap gap-2">
              @foreach ($product->sizes as $size)
                <button class="size {{ $loop->first ? 'active' : '' }} w-11 h-11 rounded-full border border-onyx/20 font-bold text-sm">{{ $size->size }}</button>
              @endforeach
            </div>
          </div>
        @endif

        <div class="mt-6">
          <p class="text-sm font-bold">Jumlah</p>
          <div class="mt-3 flex items-center gap-3">
            <button id="qtyMinus" type="button" class="w-10 h-10 rounded-full border border-onyx/20 flex items-center justify-center font-bold" aria-label="Kurangi jumlah">-</button>
            <span id="qtyValue" class="text-sm font-bold w-6 text-center">1</span>
            <button id="qtyPlus" type="button" class="w-10 h-10 rounded-full border border-onyx/20 flex items-center justify-center font-bold" aria-label="Tambah jumlah">+</button>
          </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-4">
          <button id="addToCartDetail" type="button" class="inline-flex items-center gap-2 bg-onyx text-cloud font-bold px-7 py-3.5 rounded-full hover:bg-gusto hover:text-onyx active:scale-[0.98] transition-all">
            <i class="ph-bold ph-shopping-bag"></i> Tambah ke Keranjang
          </button>
          <a id="orderLink" href="{{ $settings->whatsapp_link }}?text=Halo%2C%20saya%20mau%20pesan%20{{ urlencode($product->name) }}" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 border-2 border-onyx font-bold px-7 py-3.5 rounded-full hover:bg-onyx hover:text-cloud active:scale-[0.98] transition-all">
            Pesan Langsung
          </a>
          <button aria-label="Bagikan produk" class="w-12 h-12 rounded-full border border-onyx/20 flex items-center justify-center hover:bg-onyx hover:text-cloud transition-colors">
            <i class="ph-bold ph-share-network"></i>
          </button>
        </div>

        <div class="mt-10 divide-y divide-onyx/10 border-t border-onyx/10">
          <details open class="py-4">
            <summary class="flex items-center justify-between font-bold">
              Deskripsi Produk <i class="ph-bold ph-caret-down"></i>
            </summary>
            <p class="mt-3 text-sm text-onyx/70 leading-relaxed">{{ $product->description ?: 'Deskripsi lengkap belum ditambahkan.' }}</p>
          </details>
          <details class="py-4">
            <summary class="flex items-center justify-between font-bold">
              Ukuran dan Perawatan <i class="ph-bold ph-caret-down"></i>
            </summary>
            <p class="mt-3 text-sm text-onyx/70 leading-relaxed">Gunakan tabel ukuran pada halaman katalog sebagai acuan. Cuci dengan air dingin, hindari pemutih, dan setrika dengan suhu sedang untuk menjaga warna tetap awet.</p>
          </details>
          <details class="py-4">
            <summary class="flex items-center justify-between font-bold">
              Pengiriman <i class="ph-bold ph-caret-down"></i>
            </summary>
            <p class="mt-3 text-sm text-onyx/70 leading-relaxed">Dikirim dari Makassar ke seluruh Indonesia. Estimasi waktu kirim menyesuaikan kota tujuan dan akan diinfokan admin setelah pesanan dikonfirmasi.</p>
          </details>
        </div>
      </div>
    </div>
  </section>

  <!-- RELATED -->
  @if ($related->isNotEmpty())
  <section class="py-16 md:py-20 border-t border-onyx/10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <h2 class="text-2xl md:text-3xl font-black">Produk Terkait</h2>
      <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        @foreach ($related as $rp)
          <a href="{{ route('site.product-detail', $rp) }}" class="group bg-white overflow-hidden">
            <div class="overflow-hidden">
              @if ($rp->primary_image_url)
                <img src="{{ $rp->primary_image_url }}" alt="{{ $rp->name }}" class="w-full aspect-square object-cover group-hover:scale-[1.04] transition-transform duration-500" />
              @else
                <div class="w-full aspect-square bg-onyx/10"></div>
              @endif
            </div>
            <div class="p-4 border-t border-onyx/10">
              <p class="font-bold text-sm">{{ $rp->name }}</p>
              <p class="text-sm text-onyx/60 mt-0.5">Rp{{ number_format($rp->final_price, 0, ',', '.') }}</p>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

</main>

<x-slot:scripts>
<script>
  const thumbs = document.querySelectorAll('.thumb');
  const mainImage = document.getElementById('mainImage');
  thumbs.forEach(t => t.addEventListener('click', () => {
    thumbs.forEach(x => x.classList.remove('border-onyx'));
    t.classList.add('border-onyx');
    if (mainImage) mainImage.src = t.dataset.img;
  }));

  const wishlistBtn = document.getElementById('wishlistBtn');
  wishlistBtn?.addEventListener('click', () => {
    const active = wishlistBtn.getAttribute('aria-pressed') === 'true';
    wishlistBtn.setAttribute('aria-pressed', String(!active));
    wishlistBtn.classList.toggle('bg-onyx', !active);
    wishlistBtn.classList.toggle('text-cloud', !active);
    wishlistBtn.querySelector('i').className = !active ? 'ph-fill ph-heart text-lg' : 'ph-bold ph-heart text-lg';
  });

  const swatches = document.querySelectorAll('.swatch');
  const selectedColor = document.getElementById('selectedColor');
  const orderLink = document.getElementById('orderLink');
  const sizes = document.querySelectorAll('.size');
  let currentColor = swatches[0]?.dataset.color || '';
  let currentSize = document.querySelector('.size.active')?.textContent.trim() || '';
  let currentQty = 1;

  const qtyValue = document.getElementById('qtyValue');
  document.getElementById('qtyMinus').addEventListener('click', () => {
    currentQty = Math.max(1, currentQty - 1);
    qtyValue.textContent = currentQty;
  });
  document.getElementById('qtyPlus').addEventListener('click', () => {
    currentQty += 1;
    qtyValue.textContent = currentQty;
  });

  document.getElementById('addToCartDetail').addEventListener('click', () => {
    if (window.tigalapankaosCart) {
      const variant = [currentColor, currentSize].filter(Boolean).join(', ');
      window.tigalapankaosCart.addToCart({
        id: '{{ $product->slug }}',
        name: '{{ addslashes($product->name) }}',
        price: {{ $product->final_price }},
        variant: variant,
        image: document.getElementById('mainImage')?.src || '',
      }, currentQty);
    }
  });

  function updateOrderLink() {
    const parts = ['Halo, saya mau pesan {{ addslashes($product->name) }}'];
    if (currentColor) parts.push('warna ' + currentColor);
    if (currentSize) parts.push('ukuran ' + currentSize);
    parts.push('sebanyak ' + currentQty);
    orderLink.href = `{{ $settings->whatsapp_link }}?text=${encodeURIComponent(parts.join(' '))}`;
  }

  swatches.forEach(s => s.addEventListener('click', () => {
    swatches.forEach(x => x.classList.remove('active'));
    s.classList.add('active');
    currentColor = s.dataset.color;
    if (selectedColor) selectedColor.textContent = currentColor;
    updateOrderLink();
  }));

  sizes.forEach(s => s.addEventListener('click', () => {
    sizes.forEach(x => x.classList.remove('active'));
    s.classList.add('active');
    currentSize = s.textContent.trim();
    updateOrderLink();
  }));

  updateOrderLink();
</script>
</x-slot:scripts>

</x-site-layout>
