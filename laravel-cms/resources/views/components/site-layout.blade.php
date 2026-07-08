@php
    $navLinks = [
        ['route' => 'home', 'label' => 'Beranda'],
        ['route' => 'site.products', 'label' => 'Produk'],
        ['route' => 'site.about', 'label' => 'Tentang'],
        ['route' => 'site.articles', 'label' => 'Artikel'],
        ['route' => 'site.contact', 'label' => 'Kontak'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="order-endpoint" content="{{ route('site.order.store') }}">
<title>{{ $title ?? 'Tigalapankaos' }}</title>
<meta name="description" content="{{ $description ?? 'Tigalapankaos, supplier kaos polos cotton combed terlengkap dan terpercaya sejak 2018.' }}" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<script>
  tailwind.config = {
    theme: { extend: {
      colors: { cloud: '#EFEFEF', onyx: '#0F0F0F', silver: '#8E8E8E', gusto: '#F9A31A' },
      fontFamily: { sans: ['Montserrat', 'sans-serif'] },
    } }
  }
</script>
<style>
  html { scroll-behavior: smooth; }
  body { font-family: 'Montserrat', sans-serif; background:#EFEFEF; color:#0F0F0F; }
  .reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
  .reveal.in { opacity: 1; transform: translateY(0); }
  .italic-safe { font-style: italic; line-height: 1.1; padding-bottom: 0.15em; }
  ::selection { background:#F9A31A; color:#0F0F0F; }
  .slide { transition: opacity .6s ease; }
  .tab.active { background:#0F0F0F; color:#EFEFEF; }
  .swatch.active { outline: 2px solid #0F0F0F; outline-offset: 2px; }
  .size.active { background:#0F0F0F; color:#EFEFEF; }
  .prose-body p { margin-top: 1.1rem; line-height: 1.75; color: rgba(15,15,15,0.75); }
  .prose-body h2 { margin-top: 2rem; font-weight: 800; font-size: 1.4rem; }
</style>
{{ $head ?? '' }}
</head>
<body class="antialiased">

<!-- NAV -->
<header class="fixed top-0 inset-x-0 z-50 bg-cloud/90 backdrop-blur border-b border-onyx/10">
  <div class="max-w-[1400px] mx-auto px-5 md:px-8 h-16 md:h-[72px] flex items-center justify-between">
    <a href="{{ route('home') }}" class="shrink-0" aria-label="Tigalapankaos beranda">
      <span class="text-2xl md:text-[28px] font-black italic tracking-tight">tigalapankaos</span>
    </a>
    <nav class="hidden lg:flex items-center gap-9 text-sm font-semibold">
      @foreach ($navLinks as $link)
        <a href="{{ route($link['route']) }}" class="{{ request()->routeIs($link['route']) ? 'text-gusto' : 'hover:text-gusto transition-colors' }}">{{ $link['label'] }}</a>
      @endforeach
    </nav>
    <div class="flex items-center gap-3">
      <a href="https://wa.me/6280000000000" target="_blank" rel="noopener"
         class="hidden sm:inline-flex items-center gap-2 bg-onyx text-cloud text-sm font-bold px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx active:scale-[0.98] transition-all">
        <i class="ph-bold ph-whatsapp-logo text-base"></i> Hubungi Kami
      </a>
      <button class="cart-open-btn relative w-10 h-10 flex items-center justify-center rounded-full border border-onyx/20 hover:bg-onyx hover:text-cloud transition-colors" aria-label="Buka keranjang">
        <i class="ph-bold ph-shopping-bag text-lg"></i>
        <span class="cart-count hidden absolute -top-1.5 -right-1.5 bg-gusto text-onyx text-[10px] font-bold w-5 h-5 rounded-full items-center justify-center">0</span>
      </button>
      <button id="menuBtn" aria-label="Buka menu" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-full border border-onyx/20">
        <i class="ph-bold ph-list text-xl"></i>
      </button>
    </div>
  </div>
  <div id="mobileMenu" class="hidden lg:hidden border-t border-onyx/10 bg-cloud px-5 pb-5 pt-3">
    <nav class="flex flex-col gap-4 text-sm font-semibold">
      @foreach ($navLinks as $link)
        <a href="{{ route($link['route']) }}">{{ $link['label'] }}</a>
      @endforeach
      <a href="https://wa.me/6280000000000" class="inline-flex items-center gap-2 bg-onyx text-cloud font-bold px-5 py-2.5 rounded-full w-fit">
        <i class="ph-bold ph-whatsapp-logo"></i> Hubungi Kami
      </a>
      <button class="cart-open-btn inline-flex items-center gap-2 border border-onyx/20 font-bold px-5 py-2.5 rounded-full w-fit">
        <i class="ph-bold ph-shopping-bag"></i> Keranjang
        <span class="cart-count hidden bg-gusto text-onyx text-xs font-bold w-5 h-5 rounded-full items-center justify-center">0</span>
      </button>
    </nav>
  </div>
</header>

{{ $slot }}

<!-- FOOTER -->
<footer class="pt-16 pb-8 border-t border-onyx/10">
  <div class="max-w-[1400px] mx-auto px-5 md:px-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
    <div>
      <span class="text-2xl font-black italic-safe">tigalapankaos</span>
      <p class="mt-3 text-sm text-onyx/60 max-w-[30ch]">Supplier kaos polos cotton combed untuk mitra usaha, konveksi, dan komunitas sejak 2018.</p>
      <div class="mt-4 flex items-center gap-3 text-lg">
        <a href="#" aria-label="Instagram Tigalapankaos" class="hover:text-gusto transition-colors"><i class="ph-bold ph-instagram-logo"></i></a>
        <a href="#" aria-label="TikTok Tigalapankaos" class="hover:text-gusto transition-colors"><i class="ph-bold ph-tiktok-logo"></i></a>
        <a href="https://wa.me/6280000000000" aria-label="WhatsApp Tigalapankaos" class="hover:text-gusto transition-colors"><i class="ph-bold ph-whatsapp-logo"></i></a>
      </div>
    </div>
    <div>
      <p class="font-bold text-sm">Navigasi</p>
      <nav class="mt-4 flex flex-col gap-3 text-sm text-onyx/60">
        <a href="{{ route('site.products') }}" class="hover:text-onyx transition-colors">Produk</a>
        <a href="{{ route('site.about') }}" class="hover:text-onyx transition-colors">Tentang</a>
        <a href="{{ route('site.articles') }}" class="hover:text-onyx transition-colors">Artikel</a>
        <a href="{{ route('site.contact') }}" class="hover:text-onyx transition-colors">Kontak</a>
      </nav>
    </div>
    <div>
      <p class="font-bold text-sm">Kategori</p>
      <nav class="mt-4 flex flex-col gap-3 text-sm text-onyx/60">
        @foreach (\App\Models\Category::where('type', 'product')->orderBy('name')->get() as $footerCat)
          <a href="{{ route('site.products', ['kategori' => $footerCat->slug]) }}" class="hover:text-onyx transition-colors">Kaos {{ $footerCat->name }}</a>
        @endforeach
      </nav>
    </div>
    <div>
      <p class="font-bold text-sm">Kontak</p>
      <div class="mt-4 flex flex-col gap-3 text-sm text-onyx/60">
        <a href="https://wa.me/6280000000000" class="hover:text-onyx transition-colors">WhatsApp Admin</a>
        <a href="mailto:halo@tigalapankaos.co.id" class="hover:text-onyx transition-colors">halo@tigalapankaos.co.id</a>
        <p>Makassar, Sulawesi Selatan</p>
      </div>
    </div>
  </div>
  <div class="max-w-[1400px] mx-auto px-5 md:px-8 mt-12 pt-6 border-t border-onyx/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-onyx/50">
    <p>© {{ date('Y') }} Tigalapankaos. Seluruh hak cipta dilindungi.</p>
    <p>Melayani Sulawesi, Kalimantan, dan Jawa.</p>
  </div>
</footer>

<a href="https://wa.me/6280000000000" target="_blank" rel="noopener" aria-label="Hubungi Tigalapankaos via WhatsApp"
   class="fixed bottom-5 right-5 md:bottom-7 md:right-7 z-50 w-14 h-14 rounded-full bg-gusto text-onyx flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-transform">
  <i class="ph-bold ph-whatsapp-logo text-2xl"></i>
</a>

<script>
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.12 });
  revealEls.forEach(el => io.observe(el));
</script>
<script src="{{ asset('cart.js') }}" defer></script>
{{ $scripts ?? '' }}

</body>
</html>
