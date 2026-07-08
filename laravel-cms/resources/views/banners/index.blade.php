<x-admin-layout title="Banner / Hero Slider">

    <div class="flex justify-between items-center mb-4">
        <p class="text-sm text-onyx/60">Slide ditampilkan berurutan sesuai "Urutan", dari kecil ke besar.</p>
        <a href="{{ route('banners.create') }}" class="bg-onyx text-cloud font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">+ Banner Baru</a>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Urutan</th>
                    <th class="px-4 py-3 font-bold">Preview</th>
                    <th class="px-4 py-3 font-bold">Judul</th>
                    <th class="px-4 py-3 font-bold">Tayang</th>
                    <th class="px-4 py-3 font-bold">Status</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($banners as $banner)
                    <tr>
                        <td class="px-4 py-3 font-bold text-onyx/50">{{ $banner->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="w-20 h-12 rounded-lg bg-onyx/10 overflow-hidden">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($banner->image) }}" class="w-full h-full object-cover" alt="{{ $banner->title }}">
                            </div>
                        </td>
                        <td class="px-4 py-3 font-semibold">{{ $banner->title }}</td>
                        <td class="px-4 py-3 text-onyx/60 text-xs">
                            @if ($banner->start_at || $banner->end_at)
                                {{ $banner->start_at?->format('d M Y') ?? '...' }} - {{ $banner->end_at?->format('d M Y') ?? '...' }}
                            @else
                                Selalu tayang
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-onyx/10 text-onyx/50' }}">
                                {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('banners.edit', $banner) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Ubah</a>
                            <form action="{{ route('banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Hapus banner {{ $banner->title }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-onyx/50">Belum ada banner.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>
