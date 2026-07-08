<x-site-layout title="{{ $article->title }} - Tigalapankaos" description="{{ $article->excerpt ?? $article->title }}">

<main class="pt-16 md:pt-[72px]">

  <article class="py-10 md:py-14">
    <div class="max-w-[760px] mx-auto px-5 md:px-8">
      <p class="text-sm text-onyx/50">
        <a href="{{ route('home') }}" class="hover:text-onyx">Beranda</a> /
        <a href="{{ route('site.articles') }}" class="hover:text-onyx">Artikel</a> /
        {{ $article->title }}
      </p>
      <p class="mt-4 text-xs font-bold uppercase tracking-wide text-gusto">
        {{ $article->category->name ?? 'Artikel' }} &middot; {{ $article->published_at?->translatedFormat('d F Y') }}
      </p>
      <h1 class="mt-2 text-3xl md:text-4xl font-black leading-tight">{{ $article->title }}</h1>

      @if ($article->cover_image)
        <div class="mt-8 aspect-[16/10] overflow-hidden">
          <img src="{{ \Illuminate\Support\Facades\Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover" />
        </div>
      @endif

      <div class="prose-body">
        {!! nl2br(e($article->body)) !!}
      </div>

      <div class="mt-10 pt-6 border-t border-onyx/10 flex items-center justify-between">
        <p class="text-sm font-bold">Bagikan artikel ini</p>
        <div class="flex items-center gap-3 text-lg">
          <a href="https://wa.me/?text={{ urlencode($article->title.' - '.url()->current()) }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp" class="hover:text-gusto transition-colors"><i class="ph-bold ph-whatsapp-logo"></i></a>
          <a href="#" aria-label="Bagikan ke Instagram" class="hover:text-gusto transition-colors"><i class="ph-bold ph-instagram-logo"></i></a>
          <a href="#" aria-label="Salin tautan" class="hover:text-gusto transition-colors"><i class="ph-bold ph-link"></i></a>
        </div>
      </div>
    </div>
  </article>

  <!-- RELATED -->
  @if ($related->isNotEmpty())
  <section class="py-16 md:py-20 border-t border-onyx/10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <h2 class="text-2xl md:text-3xl font-black">Artikel Terkait</h2>
      <div class="mt-8 grid md:grid-cols-3 gap-6">
        @foreach ($related as $ra)
          <a href="{{ route('site.article-detail', $ra) }}" class="group bg-white border border-onyx/10 overflow-hidden">
            <div class="aspect-[4/3] overflow-hidden">
              @if ($ra->cover_image)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($ra->cover_image) }}" alt="{{ $ra->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" />
              @else
                <div class="w-full h-full bg-onyx/10"></div>
              @endif
            </div>
            <div class="p-5">
              @if ($ra->category) <p class="text-xs font-bold uppercase tracking-wide text-gusto">{{ $ra->category->name }}</p> @endif
              <h3 class="mt-2 font-bold leading-snug">{{ $ra->title }}</h3>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

</main>

</x-site-layout>
