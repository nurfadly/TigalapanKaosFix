@php $article = $article ?? null; @endphp

<div class="grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Judul Artikel</label>
        <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold">Kategori</label>
        <p class="text-xs text-onyx/50 mb-1">Menentukan tab filter di halaman Artikel (Bahan/Tren/Bisnis).</p>
        <select name="category_id" class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="">Tanpa kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $article->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4">
    <label class="text-sm font-bold">Ringkasan (Excerpt)</label>
    <p class="text-xs text-onyx/50 mb-1">Muncul di kartu artikel pada halaman listing. Maks 300 karakter.</p>
    <textarea name="excerpt" rows="2" maxlength="300" class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
</div>

<div class="mt-4">
    <label class="text-sm font-bold">Isi Artikel</label>
    <textarea name="body" rows="10" required class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">{{ old('body', $article->body ?? '') }}</textarea>
</div>

<div class="mt-6 grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Gambar Cover</label>
        @if ($article && $article->cover_image)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($article->cover_image) }}" class="w-full aspect-video object-cover rounded-lg mb-2">
        @endif
        <input type="file" name="cover_image" accept="image/*" class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold flex items-center gap-2">
            <input type="checkbox" name="publish_now" value="1" @checked(old('publish_now', $article?->published_at !== null))>
            Terbitkan sekarang
        </label>
        <p class="text-xs text-onyx/50 mt-1 mb-2">Atau jadwalkan tanggal terbit di bawah ini (biarkan kosong untuk simpan sebagai draft).</p>
        <input type="datetime-local" name="published_at"
               value="{{ old('published_at', $article?->published_at?->format('Y-m-d\TH:i')) }}"
               class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
</div>
