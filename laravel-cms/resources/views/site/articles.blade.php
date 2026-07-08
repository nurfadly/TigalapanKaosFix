<x-site-layout title="Artikel - Tigalapankaos" description="Tips dan kabar terbaru seputar kaos polos, bahan cotton combed, dan bisnis konveksi dari Tigalapankaos.">

<main class="pt-16 md:pt-[72px]">

  <!-- HEADER -->
  <section class="py-12 md:py-16 border-b border-onyx/10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <p class="text-sm text-onyx/50"><a href="{{ route('home') }}" class="hover:text-onyx">Beranda</a> / Artikel</p>
      <h1 class="mt-3 text-3xl md:text-5xl font-black tracking-tight">Artikel dan Tips</h1>
      <p class="mt-3 text-onyx/70 max-w-[55ch]">Panduan bahan, tren warna, dan tips bisnis konveksi dari tim Tigalapankaos.</p>
    </div>
  </section>

  <!-- FILTER -->
  <section class="py-6 border-b border-onyx/10 sticky top-16 md:top-[72px] bg-cloud/95 backdrop-blur z-30">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8 flex gap-3 overflow-x-auto">
      <a href="{{ route('site.articles') }}" class="tab {{ request('kategori') ? '' : 'active' }} shrink-0 px-5 py-2 rounded-full text-sm font-bold border border-onyx/15">Semua</a>
      @foreach ($categories as $cat)
        <a href="{{ route('site.articles', ['kategori' => $cat->slug]) }}" class="tab {{ request('kategori') === $cat->slug ? 'active' : '' }} shrink-0 px-5 py-2 rounded-full text-sm font-bold border border-onyx/15">{{ $cat->name }}</a>
      @endforeach
    </div>
  </section>

  <!-- FEATURED + GRID -->
  <section class="py-12 md:py-16">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">

      @if ($articles->isNotEmpty())
        @php $featured = $articles->first(); @endphp
        <a href="{{ route('site.article-detail', $featured) }}" class="group grid md:grid-cols-2 gap-6 bg-white border border-onyx/10 overflow-hidden">
          <div class="aspect-[4/3] md:aspect-auto overflow-hidden">
            @if ($featured->cover_image)
              <img src="{{ \Illuminate\Support\Facades\Storage::url($featured->cover_image) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" />
            @else
              <div class="w-full h-full bg-onyx/10"></div>
            @endif
          </div>
          <div class="p-6 md:p-8 flex flex-col justify-center">
            @if ($featured->category) <p class="text-xs font-bold uppercase tracking-wide text-gusto">{{ $featured->category->name }}</p> @endif
            <p class="mt-2 text-xs text-onyx/50 font-semibold">{{ $featured->published_at?->translatedFormat('d F Y') }}</p>
            <h2 class="mt-2 text-2xl font-extrabold leading-snug">{{ $featured->title }}</h2>
            @if ($featured->excerpt)
              <p class="mt-3 text-sm text-onyx/60">{{ $featured->excerpt }}</p>
            @endif
          </div>
        </a>

        <div class="mt-8 grid md:grid-cols-3 gap-6">
          @foreach ($articles->skip(1) as $article)
            <a href="{{ route('site.article-detail', $article) }}" class="group bg-white border border-onyx/10 overflow-hidden">
              <div class="aspect-[4/3] overflow-hidden">
                @if ($article->cover_image)
                  <img src="{{ \Illuminate\Support\Facades\Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" />
                @else
                  <div class="w-full h-full bg-onyx/10"></div>
                @endif
              </div>
              <div class="p-5">
                @if ($article->category) <p class="text-xs font-bold uppercase tracking-wide text-gusto">{{ $article->category->name }}</p> @endif
                <p class="mt-2 text-xs text-onyx/50 font-semibold">{{ $article->published_at?->translatedFormat('d F Y') }}</p>
                <h3 class="mt-2 font-bold leading-snug">{{ $article->title }}</h3>
              </div>
            </a>
          @endforeach
        </div>
      @else
        <p class="text-center text-onyx/50 py-16">Belum ada artikel untuk kategori ini.</p>
      @endif
    </div>
  </section>

</main>

</x-site-layout>
