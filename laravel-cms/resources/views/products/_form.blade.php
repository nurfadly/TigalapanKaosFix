@php
    $product = $product ?? null;
    $oldColors = old('colors', $product?->colors->map(fn ($c) => ['name' => $c->name, 'hex' => $c->hex])->toArray() ?? [['name' => '', 'hex' => '#0F0F0F']]);
    $oldSizes = old('sizes', $product?->sizes->pluck('size')->toArray() ?? []);
@endphp

<div class="grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Kaos Combed 30s Premium">
    </div>
    <div>
        <label class="text-sm font-bold">Kategori</label>
        <p class="text-xs text-onyx/50 mb-1">Menentukan tab yang muncul di halaman Produk (Pria/Wanita/Anak/Polo).</p>
        <select name="category_id" required class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4">
    <label class="text-sm font-bold">Deskripsi</label>
    <textarea name="description" rows="3" class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="Bahan, karakteristik, cocok untuk apa...">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mt-6 grid md:grid-cols-3 gap-6">
    <div>
        <label class="text-sm font-bold">Harga Normal (Rp)</label>
        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required min="0"
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold">Harga Diskon (Rp)</label>
        <p class="text-xs text-onyx/50 mb-1">Kosongkan kalau tidak ada promo.</p>
        <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price ?? '') }}" min="0"
               class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
            Tampilkan di website
        </label>
    </div>
</div>

<div class="mt-4 grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Diskon Mulai</label>
        <input type="datetime-local" name="discount_start"
               value="{{ old('discount_start', $product?->discount_start?->format('Y-m-d\TH:i')) }}"
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold">Diskon Berakhir</label>
        <p class="text-xs text-onyx/50 mb-1">Tampil sebagai "Promo s.d. ..." di halaman produk.</p>
        <input type="datetime-local" name="discount_end"
               value="{{ old('discount_end', $product?->discount_end?->format('Y-m-d\TH:i')) }}"
               class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<!-- WARNA -->
<div class="mt-8 pt-6 border-t border-onyx/10" x-data="colorRepeater({{ json_encode($oldColors) }})">
    <label class="text-sm font-bold">Warna Tersedia</label>
    <p class="text-xs text-onyx/50 mb-2">Muncul sebagai bulatan swatch di kartu produk dan halaman detail (sama seperti Hitam, Navy, Maroon Tua, Putih).</p>

    <template x-for="(color, index) in colors" :key="index">
        <div class="flex items-center gap-3 mb-2">
            <input type="color" :name="`colors[${index}][hex]`" x-model="color.hex" class="w-10 h-10 border border-onyx/20 rounded-lg p-0.5">
            <input type="text" :name="`colors[${index}][name]`" x-model="color.name" placeholder="Nama warna, cth. Hitam"
                   class="flex-1 border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <button type="button" @click="colors.splice(index, 1)" class="text-red-600 text-sm font-semibold" x-show="colors.length > 1">Hapus</button>
        </div>
    </template>

    <button type="button" @click="colors.push({ name: '', hex: '#0F0F0F' })" class="mt-1 text-sm font-semibold text-gusto">+ Tambah Warna</button>
</div>

<!-- UKURAN -->
<div class="mt-8 pt-6 border-t border-onyx/10">
    <label class="text-sm font-bold">Ukuran Tersedia</label>
    <p class="text-xs text-onyx/50 mb-2">Sesuai pilihan ukuran di halaman detail produk.</p>
    <div class="flex flex-wrap gap-3">
        @foreach ($sizeOptions as $size)
            <label class="flex items-center gap-2 border border-onyx/20 rounded-full px-4 py-2 text-sm font-semibold cursor-pointer">
                <input type="checkbox" name="sizes[]" value="{{ $size }}" @checked(in_array($size, $oldSizes))>
                {{ $size }}
            </label>
        @endforeach
    </div>
</div>

<!-- GAMBAR -->
<div class="mt-8 pt-6 border-t border-onyx/10">
    <label class="text-sm font-bold">Gambar Produk</label>
    <p class="text-xs text-onyx/50 mb-2">Upload satu atau beberapa foto (depan, belakang, detail, dipakai model). Gambar pertama otomatis jadi gambar utama kalau belum ada gambar utama lain.</p>

    @if ($product && $product->images->isNotEmpty())
        <div class="grid grid-cols-4 gap-3 mb-4">
            @foreach ($product->images as $img)
                <div class="relative border border-onyx/20 rounded-lg overflow-hidden">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}" class="w-full aspect-square object-cover">
                    <label class="absolute bottom-1 left-1 bg-white/90 text-[10px] font-bold px-2 py-1 rounded-full flex items-center gap-1">
                        <input type="radio" name="primary_existing_id" value="{{ $img->id }}" @checked($img->is_primary)>
                        Utama
                    </label>
                    <label class="absolute top-1 right-1 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-full flex items-center gap-1">
                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                        Hapus
                    </label>
                </div>
            @endforeach
        </div>
    @endif

    <input type="file" name="images[]" multiple accept="image/*" class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    <div class="mt-2">
        <label class="text-xs font-semibold">Jadikan gambar utama dari file baru di atas (isi angka urutan, 0 = file pertama, kosongkan kalau tidak perlu)</label>
        <input type="number" name="primary_index" min="0" class="mt-1 w-32 border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function colorRepeater(initial) {
        return {
            colors: initial && initial.length ? initial : [{ name: '', hex: '#0F0F0F' }],
        };
    }
</script>
