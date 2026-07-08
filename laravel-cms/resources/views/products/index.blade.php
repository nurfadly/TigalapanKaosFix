<x-admin-layout title="Produk">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <select name="category" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="border border-onyx/20 rounded-lg px-4 py-2 text-sm font-semibold hover:bg-onyx/5">Filter</button>
        </form>
        <a href="{{ route('products.create') }}" class="bg-onyx text-cloud font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">+ Produk Baru</a>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Produk</th>
                    <th class="px-4 py-3 font-bold">Kategori</th>
                    <th class="px-4 py-3 font-bold">Harga</th>
                    <th class="px-4 py-3 font-bold">Status</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-onyx/10 overflow-hidden shrink-0">
                                @if ($product->images->first())
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($product->images->first()->path) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                @endif
                            </div>
                            <span class="font-semibold">{{ $product->name }}</span>
                        </td>
                        <td class="px-4 py-3 text-onyx/60">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($product->is_on_sale)
                                <span class="font-bold text-gusto">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                <span class="text-onyx/40 line-through text-xs ml-1">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            @else
                                <span class="font-semibold">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-onyx/10 text-onyx/50' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('products.edit', $product) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Ubah</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk {{ $product->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-onyx/50">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>

</x-admin-layout>
