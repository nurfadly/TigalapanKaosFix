<x-admin-layout title="Kategori">

    <div class="flex justify-end mb-4">
        <a href="{{ route('categories.create') }}" class="bg-onyx text-cloud font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">+ Kategori Baru</a>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Nama</th>
                    <th class="px-4 py-3 font-bold">Tipe</th>
                    <th class="px-4 py-3 font-bold">Jumlah Item</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $category->name }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $category->type === 'product' ? 'bg-gusto/20 text-gusto' : 'bg-onyx/10 text-onyx/70' }}">
                                {{ $category->type === 'product' ? 'Produk' : 'Artikel' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-onyx/60">
                            {{ $category->type === 'product' ? $category->products_count : $category->articles_count }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('categories.edit', $category) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Ubah</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori {{ $category->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-onyx/50">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>
