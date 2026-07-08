<x-admin-layout title="Artikel">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari artikel..." class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <select name="category" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="border border-onyx/20 rounded-lg px-4 py-2 text-sm font-semibold hover:bg-onyx/5">Filter</button>
        </form>
        <a href="{{ route('articles.create') }}" class="bg-onyx text-cloud font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">+ Artikel Baru</a>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Judul</th>
                    <th class="px-4 py-3 font-bold">Kategori</th>
                    <th class="px-4 py-3 font-bold">Status</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($articles as $article)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $article->title }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $article->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($article->published_at && $article->published_at->lte(now()))
                                <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-green-100 text-green-700">Terbit</span>
                            @elseif ($article->published_at)
                                <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-gusto/20 text-gusto">Terjadwal</span>
                            @else
                                <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-onyx/10 text-onyx/50">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('articles.edit', $article) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Ubah</a>
                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel {{ $article->title }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-onyx/50">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $articles->links() }}</div>

</x-admin-layout>
