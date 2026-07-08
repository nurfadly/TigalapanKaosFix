<x-admin-layout title="Stok Outlet">

    <div class="grid md:grid-cols-3 gap-6 mb-6">
        <form action="{{ route('stock.store') }}" method="POST" enctype="multipart/form-data" class="md:col-span-2 bg-white rounded-xl border border-onyx/10 p-6">
            @csrf
            <p class="font-bold">Upload File Stok</p>
            <p class="text-xs text-onyx/50 mt-1">Format .csv atau .xlsx hasil export dari sistem kasir/POS (kolom: Name - Variant, Category, Outlet, Beginning, Purchase Order, Sales, Transfer, Adjustment, Ending).</p>
            <p class="text-xs text-onyx/50 mt-1">Mengupload file baru akan <strong>menimpa total</strong> data stok saat ini. Riwayat ringkasan tiap upload tetap tersimpan di bawah.</p>
            <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" required class="mt-4 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <button type="submit" class="mt-4 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Proses Upload</button>
        </form>

        <div class="bg-white rounded-xl border border-onyx/10 p-6">
            <p class="font-bold">Data Saat Ini</p>
            @if ($latest)
                <p class="mt-2 text-sm text-onyx/60">{{ number_format($latest->total_items, 0, ',', '.') }} item unik</p>
                <p class="text-sm text-onyx/60">{{ $latest->total_outlets }} outlet</p>
                <p class="text-sm text-onyx/60">{{ $latest->total_matched }} sudah tertaut produk</p>
                <p class="mt-2 text-xs text-onyx/40">Update terakhir: {{ $latest->created_at->translatedFormat('d F Y, H:i') }}</p>
            @else
                <p class="mt-2 text-sm text-onyx/50">Belum ada data stok.</p>
            @endif
            <a href="{{ route('stock.data') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-gusto">Lihat Data Stok <i class="ph-bold ph-arrow-right"></i></a>
            <br>
            <a href="{{ route('stock.match') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-gusto">Pencocokan Produk <i class="ph-bold ph-arrow-right"></i></a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">File</th>
                    <th class="px-4 py-3 font-bold">Baris</th>
                    <th class="px-4 py-3 font-bold">Item Unik</th>
                    <th class="px-4 py-3 font-bold">Outlet</th>
                    <th class="px-4 py-3 font-bold">Tertaut Produk</th>
                    <th class="px-4 py-3 font-bold">Diupload Oleh</th>
                    <th class="px-4 py-3 font-bold">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($imports as $import)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $import->filename }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ number_format($import->total_rows, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ number_format($import->total_items, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $import->total_outlets }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $import->total_matched }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $import->importer->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-onyx/50 text-xs">{{ $import->created_at->translatedFormat('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-onyx/50">Belum ada riwayat upload.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>
