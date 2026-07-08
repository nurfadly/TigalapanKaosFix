@php $testimonial = $testimonial ?? null; @endphp

<div>
    <label class="text-sm font-bold">Kutipan Testimoni</label>
    <textarea name="quote" rows="4" required class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">{{ old('quote', $testimonial->quote ?? '') }}</textarea>
</div>

<div class="mt-4 grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Nama</label>
        <input type="text" name="author_name" value="{{ old('author_name', $testimonial->author_name ?? '') }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Andi Prasetyo">
    </div>
    <div>
        <label class="text-sm font-bold">Jabatan / Keterangan</label>
        <input type="text" name="author_title" value="{{ old('author_title', $testimonial->author_title ?? '') }}"
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Pemilik Konveksi Anugerah Jaya, Makassar">
    </div>
</div>

<div class="mt-4 grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Urutan Tampil</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $testimonial->sort_order ?? ($nextOrder ?? 0)) }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div class="flex items-end pb-2">
        <label class="text-sm font-bold flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active ?? true))>
            Tampilkan di beranda
        </label>
    </div>
</div>
