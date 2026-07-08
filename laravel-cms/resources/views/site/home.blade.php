<x-site-layout title="Tigalapankaos - Supplier Kaos Polos Terlengkap" description="Tigalapankaos, supplier kaos polos cotton combed terlengkap dan terpercaya untuk mitra bisnis di Sulawesi, Kalimantan, dan Jawa sejak 2018.">

<main>

  <!-- HERO SLIDER -->
  @if ($banners->isNotEmpty())
    <section class="min-h-[100dvh] relative overflow-hidden" id="heroSlider" aria-roledescription="carousel" aria-label="Sorotan Tigalapankaos">
      @foreach ($banners as $i => $banner)
        <div class="slide absolute inset-0 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}" data-slide="{{ $i }}">
          <img src="{{ \Illuminate\Support\Facades\Storage::url($banner->image) }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover" />
          <div class="absolute inset-0 bg-gradient-to-t from-onyx/85 via-onyx/15 to-transparent"></div>
          <div class="absolute inset-0 flex flex-col justify-end">
            <div class="max-w-[1400px] mx-auto px-5 md:px-8 w-full pb-20 md:pb-24">
              <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="max-w-[20ch]">
                  <h1 class="text-4xl md:text-6xl font-black leading-[1.05] tracking-tight text-cloud">{{ $banner->title }}</h1>
                  @if ($banner->description)
                    <p class="mt-4 text-base md:text-lg text-cloud/80 max-w-[46ch] leading-relaxed">{{ $banner->description }}</p>
                  @endif
                </div>
                @if ($banner->cta_text)
                  <a href="{{ $banner->cta_link ?: route('site.products') }}" class="shrink-0 inline-flex items-center gap-2 bg-cloud text-onyx font-bold px-7 py-3.5 rounded-full hover:bg-gusto active:scale-[0.98] transition-all">
                    {{ $banner->cta_text }} <i class="ph-bold ph-arrow-right"></i>
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach

      @if ($banners->count() > 1)
        <button id="prevSlide" aria-label="Slide sebelumnya" class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-cloud/90 backdrop-blur items-center justify-center hover:bg-cloud transition-colors z-10">
          <i class="ph-bold ph-caret-left"></i>
        </button>
        <button id="nextSlide" aria-label="Slide berikutnya" class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-cloud/90 backdrop-blur items-center justify-center hover:bg-cloud transition-colors z-10">
          <i class="ph-bold ph-caret-right"></i>
        </button>
        <div class="absolute bottom-6 inset-x-0 flex items-center justify-center gap-2 z-10">
          @foreach ($banners as $i => $banner)
            <button class="dot w-2.5 h-2.5 rounded-full {{ $i === 0 ? 'bg-cloud' : 'bg-cloud/35' }}" data-dot="{{ $i }}" aria-label="Ke slide {{ $i + 1 }}"></button>
          @endforeach
        </div>
      @endif
    </section>
  @endif

  <!-- PRODUCTS PREVIEW -->
  @if ($featuredProducts->isNotEmpty())
  <section id="produk" class="py-20 md:py-28 reveal">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <p class="text-[11px] uppercase tracking-[0.18em] font-bold text-gusto">Produk Unggulan</p>
      <div class="mt-3 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <h2 class="text-3xl md:text-4xl font-black leading-tight max-w-[18ch]">Produk yang Paling Sering Dipesan Mitra Kami</h2>
        <a href="{{ route('site.products') }}" class="inline-flex items-center gap-2 font-bold hover:text-gusto transition-colors shrink-0">
          Lihat Katalog <i class="ph-bold ph-arrow-right"></i>
        </a>
      </div>

      <div class="mt-10 grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        @foreach ($featuredProducts as $index => $product)
          @php
            $detailUrl = route('site.product-detail', $product);
            $firstColor = $product->colors->first();
          @endphp
          <div class="group relative overflow-hidden bg-white {{ $index === 0 ? 'col-span-2 row-span-2' : '' }}">
            <a href="{{ $detailUrl }}" class="block {{ $index === 0 ? 'absolute inset-0' : 'overflow-hidden' }}">
              @if ($product->primary_image_url)
                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full {{ $index === 0 ? 'object-cover opacity-90' : 'aspect-square object-cover' }} group-hover:scale-[1.04] transition-transform duration-500" />
              @else
                <div class="w-full {{ $index === 0 ? 'h-full' : 'aspect-square' }} bg-onyx/10"></div>
              @endif
            </a>
            @if ($product->is_on_sale)
              <div class="absolute top-3 left-3 bg-gusto text-onyx text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 pointer-events-none">Diskon</div>
            @endif

            @if ($index === 0)
              <div class="absolute inset-x-0 bottom-0 p-5 bg-gradient-to-t from-onyx/90 to-transparent text-cloud flex items-end justify-between gap-3 pointer-events-none">
                <div>
                  <p class="font-bold">{{ $product->name }}</p>
                  <p class="text-sm mt-1">
                    @if ($product->is_on_sale)
                      <span class="font-bold text-gusto">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</span>
                      <span class="text-cloud/50 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    @else
                      <span class="font-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                    @if ($firstColor) <span class="text-cloud/70">&middot; {{ $firstColor->name }}</span> @endif
                  </p>
                  @if ($product->is_on_sale && $product->discount_end)
                    <p class="text-[11px] text-cloud/60 mt-0.5">Promo s.d. {{ $product->discount_end->translatedFormat('d M Y') }}</p>
                  @endif
                  @if ($product->colors->isNotEmpty())
                    <div class="mt-2 flex items-center gap-1" aria-hidden="true">
                      @foreach ($product->colors as $color)
                        <span class="w-3.5 h-3.5 rounded-full ring-1 ring-offset-1 ring-offset-onyx ring-cloud" style="background:{{ $color->hex }}"></span>
                      @endforeach
                    </div>
                  @endif
                </div>
                <button class="add-to-cart pointer-events-auto shrink-0 w-10 h-10 rounded-full bg-cloud text-onyx flex items-center justify-center hover:bg-gusto transition-colors" aria-label="Tambah ke keranjang"
                  data-id="{{ $product->slug }}" data-name="{{ $product->name }}" data-price="{{ $product->final_price }}" data-variant="{{ $firstColor->name ?? '' }}" data-image="{{ $product->primary_image_url }}">
                  <i class="ph-bold ph-plus"></i>
                </button>
              </div>
            @else
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
                    @if ($firstColor) <span class="text-onyx/60">&middot; {{ $firstColor->name }}</span> @endif
                  </p>
                  @if ($product->colors->isNotEmpty())
                    <div class="mt-1.5 flex items-center gap-1" aria-hidden="true">
                      @foreach ($product->colors as $color)
                        <span class="w-3.5 h-3.5 rounded-full ring-1 ring-offset-1 ring-onyx" style="background:{{ $color->hex }}"></span>
                      @endforeach
                    </div>
                    <p class="sr-only">Tersedia warna: {{ $product->colors->pluck('name')->implode(', ') }}</p>
                  @endif
                </a>
                <button class="add-to-cart shrink-0 w-9 h-9 rounded-full bg-onyx text-cloud flex items-center justify-center hover:bg-gusto hover:text-onyx transition-colors" aria-label="Tambah ke keranjang"
                  data-id="{{ $product->slug }}" data-name="{{ $product->name }}" data-price="{{ $product->final_price }}" data-variant="{{ $firstColor->name ?? '' }}" data-image="{{ $product->primary_image_url }}">
                  <i class="ph-bold ph-plus"></i>
                </button>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- STATS -->
  <section class="py-14 md:py-16 border-y border-onyx/10 reveal">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8 grid sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-onyx/10">
      <div class="py-6 sm:py-0 sm:px-6 first:pl-0">
        <p class="text-4xl md:text-5xl font-black text-gusto">6+</p>
        <p class="mt-2 text-sm text-onyx/70">Tahun melayani mitra bisnis di Indonesia Timur</p>
      </div>
      <div class="py-6 sm:py-0 sm:px-6">
        <p class="text-4xl md:text-5xl font-black text-gusto">20+</p>
        <p class="mt-2 text-sm text-onyx/70">Pilihan warna kain cotton combed</p>
      </div>
      <div class="py-6 sm:py-0 sm:px-6">
        <p class="text-4xl md:text-5xl font-black text-gusto">3</p>
        <p class="mt-2 text-sm text-onyx/70">Wilayah distribusi utama: Sulawesi, Kalimantan, Jawa</p>
      </div>
    </div>
  </section>

  <!-- TESTIMONIAL -->
  @if ($testimonials->isNotEmpty())
  <section class="py-20 md:py-28 reveal" id="testimonialSlider">
    <div class="max-w-[900px] mx-auto px-5 md:px-8 text-center">
      <i class="ph-fill ph-quotes text-3xl text-gusto"></i>
      @foreach ($testimonials as $testimonial)
        <div class="testimonial-slide {{ $loop->first ? '' : 'hidden' }}" data-slide="{{ $loop->index }}">
          <p class="mt-6 text-xl md:text-3xl font-semibold leading-snug max-w-[28ch] mx-auto">
            {{ $testimonial->quote }}
          </p>
          <p class="mt-6 text-sm text-onyx/60 font-semibold">
            {{ $testimonial->author_name }}{{ $testimonial->author_title ? ' - '.$testimonial->author_title : '' }}
          </p>
        </div>
      @endforeach

      @if ($testimonials->count() > 1)
        <div class="mt-6 flex items-center justify-center gap-2">
          @foreach ($testimonials as $testimonial)
            <button class="testimonial-dot w-2 h-2 rounded-full {{ $loop->first ? 'bg-onyx' : 'bg-onyx/20' }}" data-dot="{{ $loop->index }}" aria-label="Testimoni {{ $loop->iteration }}"></button>
          @endforeach
        </div>
      @endif
    </div>
  </section>
  @endif

  <!-- ARTICLES PREVIEW -->
  @if ($latestArticles->isNotEmpty())
  <section class="py-20 md:py-28 border-t border-onyx/10 reveal">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <p class="text-[11px] uppercase tracking-[0.18em] font-bold text-gusto">Artikel Terbaru</p>
      <div class="mt-3 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <h2 class="text-3xl md:text-4xl font-black leading-tight max-w-[22ch]">Tips dan Kabar Terbaru dari Tigalapankaos</h2>
        <a href="{{ route('site.articles') }}" class="inline-flex items-center gap-2 font-bold hover:text-gusto transition-colors shrink-0">
          Lihat Semua Artikel <i class="ph-bold ph-arrow-right"></i>
        </a>
      </div>

      <div class="mt-10 grid lg:grid-cols-3 gap-6">
        @php $featuredArticle = $latestArticles->first(); @endphp
        <a href="{{ route('site.article-detail', $featuredArticle) }}" class="group lg:col-span-2 grid sm:grid-cols-2 gap-5 bg-white border border-onyx/10 overflow-hidden">
          <div class="aspect-[4/3] sm:aspect-auto overflow-hidden">
            @if ($featuredArticle->cover_image)
              <img src="{{ \Illuminate\Support\Facades\Storage::url($featuredArticle->cover_image) }}" alt="{{ $featuredArticle->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" />
            @else
              <div class="w-full h-full bg-onyx/10"></div>
            @endif
          </div>
          <div class="p-5 sm:pr-6 flex flex-col justify-center">
            <p class="text-xs text-onyx/50 font-semibold">{{ $featuredArticle->published_at?->translatedFormat('d F Y') }}</p>
            <h3 class="mt-2 font-extrabold text-lg leading-snug">{{ $featuredArticle->title }}</h3>
            @if ($featuredArticle->excerpt)
              <p class="mt-2 text-sm text-onyx/60">{{ $featuredArticle->excerpt }}</p>
            @endif
          </div>
        </a>
        <div class="grid gap-6">
          @foreach ($latestArticles->skip(1) as $article)
            <a href="{{ route('site.article-detail', $article) }}" class="group flex gap-4 bg-white border border-onyx/10 p-4">
              <div class="w-24 h-24 shrink-0 overflow-hidden">
                @if ($article->cover_image)
                  <img src="{{ \Illuminate\Support\Facades\Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" />
                @else
                  <div class="w-full h-full bg-onyx/10"></div>
                @endif
              </div>
              <div>
                <p class="text-xs text-onyx/50 font-semibold">{{ $article->published_at?->translatedFormat('d F Y') }}</p>
                <h3 class="mt-1 font-bold text-sm leading-snug">{{ $article->title }}</h3>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- CTA BANNER -->
  <section class="reveal">
    <div class="bg-gusto">
      <div class="max-w-[1400px] mx-auto px-5 md:px-8 py-14 md:py-16 flex flex-col md:flex-row items-center justify-between gap-6 text-onyx">
        <div>
          <h2 class="text-2xl md:text-3xl font-black max-w-[20ch]">Siap Pesan dalam Jumlah Besar?</h2>
          <p class="mt-2 text-onyx/80 max-w-[48ch]">Admin kami siap bantu hitung kebutuhan stok dan kirim penawaran harga grosir hari ini juga.</p>
        </div>
        <a href="https://wa.me/6280000000000" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-onyx text-cloud font-bold px-7 py-3.5 rounded-full hover:opacity-85 active:scale-[0.98] transition-all shrink-0">
          <i class="ph-bold ph-whatsapp-logo"></i> Hubungi Kami
        </a>
      </div>
    </div>
  </section>

</main>

<x-slot:scripts>
<script>
  // Testimonial rotation (kalau lebih dari satu testimoni aktif)
  (function () {
    const root = document.getElementById('testimonialSlider');
    if (!root) return;
    const slides = root.querySelectorAll('.testimonial-slide');
    const dots = root.querySelectorAll('.testimonial-dot');
    if (slides.length < 2) return;
    let current = 0;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function show(index) {
      slides.forEach((s, i) => s.classList.toggle('hidden', i !== index));
      dots.forEach((d, i) => {
        d.classList.toggle('bg-onyx', i === index);
        d.classList.toggle('bg-onyx/20', i !== index);
      });
      current = index;
    }

    dots.forEach(d => d.addEventListener('click', () => show(parseInt(d.dataset.dot))));

    if (!reduceMotion) {
      setInterval(() => show((current + 1) % slides.length), 7000);
    }
  })();

  // Hero slider
  (function () {
    const root = document.getElementById('heroSlider');
    if (!root) return;
    const slides = root.querySelectorAll('.slide');
    const dots = root.querySelectorAll('.dot');
    if (slides.length < 2) return;
    let current = 0;
    let timer;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function show(index) {
      slides.forEach((s, i) => {
        const active = i === index;
        s.classList.toggle('opacity-100', active);
        s.classList.toggle('opacity-0', !active);
        s.classList.toggle('pointer-events-none', !active);
      });
      dots.forEach((d, i) => {
        d.classList.toggle('bg-cloud', i === index);
        d.classList.toggle('bg-cloud/35', i !== index);
      });
      current = index;
    }

    function nextSlide() { show((current + 1) % slides.length); }
    function prevSlide() { show((current - 1 + slides.length) % slides.length); }

    function startAutoplay() {
      if (reduceMotion) return;
      stopAutoplay();
      timer = setInterval(nextSlide, 6000);
    }
    function stopAutoplay() { if (timer) clearInterval(timer); }

    document.getElementById('nextSlide')?.addEventListener('click', () => { nextSlide(); startAutoplay(); });
    document.getElementById('prevSlide')?.addEventListener('click', () => { prevSlide(); startAutoplay(); });
    dots.forEach(d => d.addEventListener('click', () => { show(parseInt(d.dataset.dot)); startAutoplay(); }));

    root.addEventListener('mouseenter', stopAutoplay);
    root.addEventListener('mouseleave', startAutoplay);
    root.addEventListener('focusin', stopAutoplay);
    root.addEventListener('focusout', startAutoplay);

    show(0);
    startAutoplay();
  })();
</script>
</x-slot:scripts>

</x-site-layout>
