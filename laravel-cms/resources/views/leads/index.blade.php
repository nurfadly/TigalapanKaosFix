<x-admin-layout title="Leads">

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / telepon / email..." class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
        <select name="status" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Status</option>
            @foreach (\App\Models\Lead::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="border border-onyx/20 rounded-lg px-4 py-2 text-sm font-semibold hover:bg-onyx/5">Filter</button>
    </form>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Nama</th>
                    <th class="px-4 py-3 font-bold">Kontak</th>
                    <th class="px-4 py-3 font-bold">Topik</th>
                    <th class="px-4 py-3 font-bold">Status</th>
                    <th class="px-4 py-3 font-bold">Waktu</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($leads as $lead)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $lead->name }}</td>
                        <td class="px-4 py-3 text-onyx/60">
                            <p>{{ $lead->phone }}</p>
                            @if ($lead->email)<p class="text-xs text-onyx/40">{{ $lead->email }}</p>@endif
                        </td>
                        <td class="px-4 py-3 text-onyx/60">{{ $lead->topic }}</td>
                        <td class="px-4 py-3">
                            @php
                                $badge = match($lead->status) {
                                    'new' => 'bg-gusto/20 text-gusto',
                                    'contacted' => 'bg-blue-100 text-blue-700',
                                    'closed' => 'bg-green-100 text-green-700',
                                    default => 'bg-onyx/10 text-onyx/60',
                                };
                            @endphp
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $badge }}">{{ $lead->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-onyx/50 text-xs">{{ $lead->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('leads.show', $lead) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-onyx/50">Belum ada leads masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $leads->links() }}</div>

</x-admin-layout>
