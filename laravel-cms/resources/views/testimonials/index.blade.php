<x-admin-layout title="Testimoni">

    <div class="flex justify-end mb-4">
        <a href="{{ route('testimonials.create') }}" class="bg-onyx text-cloud font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">+ Testimoni Baru</a>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Urutan</th>
                    <th class="px-4 py-3 font-bold">Kutipan</th>
                    <th class="px-4 py-3 font-bold">Nama</th>
                    <th class="px-4 py-3 font-bold">Status</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td class="px-4 py-3 font-bold text-onyx/50">{{ $testimonial->sort_order }}</td>
                        <td class="px-4 py-3 text-onyx/70 max-w-md truncate">{{ $testimonial->quote }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $testimonial->author_name }}</p>
                            <p class="text-xs text-onyx/50">{{ $testimonial->author_title }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $testimonial->is_active ? 'bg-green-100 text-green-700' : 'bg-onyx/10 text-onyx/50' }}">
                                {{ $testimonial->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('testimonials.edit', $testimonial) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Ubah</a>
                            <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Hapus testimoni ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-onyx/50">Belum ada testimoni.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>
