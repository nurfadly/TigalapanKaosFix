@php $category = $category ?? null; @endphp

<div>
    <label class="text-sm font-bold">Nama Kategori</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
           class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Pria, Anak, Bahan, Tren">
</div>

<div class="mt-4">
    <label class="text-sm font-bold">Tipe</label>
    <p class="text-xs text-onyx/50 mb-2">Produk = tab filter di halaman Produk. Artikel = tab filter di halaman Artikel.</p>
    <select name="type" required class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
        <option value="product" @selected(old('type', $category->type ?? '') === 'product')>Produk</option>
        <option value="article" @selected(old('type', $category->type ?? '') === 'article')>Artikel</option>
    </select>
</div>
