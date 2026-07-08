<x-admin-layout title="Cabang">

    <div class="flex justify-end mb-4">
        <a href="{{ route('branches.create') }}" class="bg-onyx text-cloud font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">+ Cabang Baru</a>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Urutan</th>
                    <th class="px-4 py-3 font-bold">Label</th>
                    <th class="px-4 py-3 font-bold">Kota</th>
                    <th class="px-4 py-3 font-bold">Provinsi</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($branches as $branch)
                    <tr>
                        <td class="px-4 py-3 font-bold text-onyx/50">{{ $branch->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $branch->is_hq ? 'bg-gusto/20 text-gusto' : 'bg-onyx/10 text-onyx/70' }}">{{ $branch->display_label }}</span>
                        </td>
                        <td class="px-4 py-3 font-semibold">{{ $branch->city }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $branch->province }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('branches.edit', $branch) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Ubah</a>
                            <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="inline" onsubmit="return confirm('Hapus cabang {{ $branch->city }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-onyx/50">Belum ada cabang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>
