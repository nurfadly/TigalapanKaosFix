<x-admin-layout title="Pencocokan Stok ke Produk">

    <p class="text-sm text-onyx/60 mb-4">
        Sistem sudah mencoba mencocokkan otomatis nama item dari file stok ke Produk di katalog berdasarkan kemiripan teks.
        Karena format penamaan POS dan katalog berbeda jauh, hasilnya belum tentu tepat — cek dan perbaiki manual di sini kalau perlu.
        Pencocokan berlaku untuk semua outlet sekaligus.
    </p>

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama item..." class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
        <select name="status" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="unmatched" @selected($status === 'unmatched')>Belum Tertaut</option>
            <option value="matched" @selected($status === 'matched')>Sudah Tertaut</option>
            <option value="all" @selected($status === 'all')>Semua</option>
        </select>
        <button type="submit" class="border border-onyx/20 rounded-lg px-4 py-2 text-sm font-semibold hover:bg-onyx/5">Filter</button>
        <a href="{{ route('stock.index') }}" class="ml-auto text-sm font-semibold text-onyx/60 hover:text-onyx">&larr; Kembali</a>
    </form>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Nama Item (POS)</th>
                    <th class="px-4 py-3 font-bold">Kategori</th>
                    <th class="px-4 py-3 font-bold">Skor Otomatis</th>
                    <th class="px-4 py-3 font-bold">Produk Tertaut</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($catalog as $entry)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $entry->raw_name }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $entry->category ?? '-' }}</td>
                        <td class="px-4 py-3 text-onyx/60">
                            @if ($entry->matched_manually)
                                <span class="text-xs text-onyx/40">manual</span>
                            @elseif ($entry->match_score !== null)
                                {{ number_format($entry->match_score * 100, 0) }}%
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('stock.match.update', $entry) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <select name="matched_product_id" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
                                    <option value="">- Tidak tertaut -</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected($entry->matched_product_id === $product->id)>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="text-sm font-semibold text-gusto">Simpan</button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('stock.data', ['q' => $entry->raw_name]) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Lihat Stok</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-onyx/50">Tidak ada item untuk filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $catalog->links() }}</div>

</x-admin-layout>
