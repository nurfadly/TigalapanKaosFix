@php $banner = $banner ?? null; @endphp

<div>
    <label class="text-sm font-bold">Judul (Headline)</label>
    <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" required
           class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="Muncul besar di kiri-bawah slide">
</div>

<div class="mt-4">
    <label class="text-sm font-bold">Deskripsi</label>
    <textarea name="description" rows="2" maxlength="300" class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">{{ old('description', $banner->description ?? '') }}</textarea>
</div>

<div class="mt-4 grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Teks Tombol CTA</label>
        <input type="text" name="cta_text" value="{{ old('cta_text', $banner->cta_text ?? '') }}"
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Lihat Produk">
    </div>
    <div>
        <label class="text-sm font-bold">Link Tombol CTA</label>
        <input type="text" name="cta_link" value="{{ old('cta_link', $banner->cta_link ?? '') }}"
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. /produk">
    </div>
</div>

<div class="mt-6">
    <label class="text-sm font-bold">Gambar Slide</label>
    <p class="text-xs text-onyx/50 mb-1">Gambar full-bleed, sebaiknya ukuran lebar (mis. 1920x1080).</p>
    @if ($banner && $banner->image)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($banner->image) }}" class="w-full aspect-video object-cover rounded-lg mb-2">
    @endif
    <input type="file" name="image" accept="image/*" @required(!$banner) class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
</div>

<div class="mt-6 grid md:grid-cols-3 gap-6">
    <div>
        <label class="text-sm font-bold">Urutan Tampil</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $banner->sort_order ?? ($nextOrder ?? 0)) }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold">Mulai Tayang</label>
        <input type="datetime-local" name="start_at" value="{{ old('start_at', $banner?->start_at?->format('Y-m-d\TH:i')) }}"
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold">Berakhir Tayang</label>
        <input type="datetime-local" name="end_at" value="{{ old('end_at', $banner?->end_at?->format('Y-m-d\TH:i')) }}"
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<div class="mt-4">
    <label class="text-sm font-bold flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))>
        Aktifkan slide ini
    </label>
</div>
