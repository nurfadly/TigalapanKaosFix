@php $branch = $branch ?? null; @endphp

<div class="grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Kota</label>
        <input type="text" name="city" value="{{ old('city', $branch->city ?? '') }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Makassar">
    </div>
    <div>
        <label class="text-sm font-bold">Provinsi</label>
        <input type="text" name="province" value="{{ old('province', $branch->province ?? '') }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Sulawesi Selatan">
    </div>
</div>

<div class="mt-4 grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Label (opsional)</label>
        <p class="text-xs text-onyx/50 mb-1">Kosongkan untuk pakai default "Kantor Pusat" / "Cabang" sesuai centang di bawah.</p>
        <input type="text" name="label" value="{{ old('label', $branch->label ?? '') }}"
               class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm" placeholder="cth. Titik Distribusi">
    </div>
    <div>
        <label class="text-sm font-bold">Urutan Tampil</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $branch->sort_order ?? ($nextOrder ?? 0)) }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<div class="mt-4">
    <label class="text-sm font-bold flex items-center gap-2">
        <input type="checkbox" name="is_hq" value="1" @checked(old('is_hq', $branch->is_hq ?? false))>
        Ini adalah kantor pusat
    </label>
</div>
