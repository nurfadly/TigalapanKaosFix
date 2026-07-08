<x-admin-layout title="Data Stok Outlet">

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama item..." class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
        <select name="outlet" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Outlet</option>
            @foreach ($outlets as $outlet)
                <option value="{{ $outlet }}" @selected(request('outlet') === $outlet)>{{ $outlet }}</option>
            @endforeach
        </select>
        <select name="kategori" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(request('kategori') === $category)>{{ $category }}</option>
            @endforeach
        </select>
        <button type="submit" class="border border-onyx/20 rounded-lg px-4 py-2 text-sm font-semibold hover:bg-onyx/5">Filter</button>
        <a href="{{ route('stock.index') }}" class="ml-auto text-sm font-semibold text-onyx/60 hover:text-onyx">&larr; Kembali</a>
    </form>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Nama Item</th>
                    <th class="px-4 py-3 font-bold">Kategori</th>
                    <th class="px-4 py-3 font-bold">Outlet</th>
                    <th class="px-4 py-3 font-bold">Produk Tertaut</th>
                    <th class="px-4 py-3 font-bold text-right">Stok Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($items as $item)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $item->catalog->raw_name }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $item->catalog->category ?? '-' }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $item->outlet }}</td>
                        <td class="px-4 py-3">
                            @if ($item->catalog->product)
                                <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-green-100 text-green-700">{{ $item->catalog->product->name }}</span>
                            @else
                                <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-onyx/10 text-onyx/50">Belum tertaut</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($item->ending, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-onyx/50">Belum ada data stok. Upload file dulu di halaman Stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>

</x-admin-layout>
