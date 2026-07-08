<x-admin-layout title="Detail Lead">

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-xl border border-onyx/10 p-6">
            <p class="text-xs font-bold uppercase text-onyx/50">Topik</p>
            <p class="mt-1 font-bold">{{ $lead->topic }}</p>
            <p class="mt-4 text-xs font-bold uppercase text-onyx/50">Pesan</p>
            <p class="mt-1 text-sm text-onyx/70 leading-relaxed whitespace-pre-line">{{ $lead->message }}</p>
            <p class="mt-4 text-xs text-onyx/40">Dikirim {{ $lead->created_at->translatedFormat('d F Y, H:i') }}</p>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-onyx/10 p-5">
                <p class="text-xs font-bold uppercase text-onyx/50">Kontak</p>
                <p class="mt-2 font-bold">{{ $lead->name }}</p>
                <p class="text-sm text-onyx/60">{{ $lead->phone }}</p>
                @if ($lead->email)<p class="text-sm text-onyx/60">{{ $lead->email }}</p>@endif
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-gusto">
                    <i class="ph-bold ph-whatsapp-logo"></i> Chat WhatsApp
                </a>
            </div>

            <form action="{{ route('leads.update', $lead) }}" method="POST" class="bg-white rounded-xl border border-onyx/10 p-5">
                @csrf
                @method('PUT')
                <label class="text-sm font-bold">Status</label>
                <select name="status" class="mt-2 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
                    @foreach (\App\Models\Lead::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($lead->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="mt-4 w-full bg-onyx text-cloud font-bold px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan</button>
            </form>

            <form action="{{ route('leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Hapus lead ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full text-sm font-semibold text-red-600 hover:text-red-800 py-2">Hapus Lead</button>
            </form>
        </div>
    </div>

</x-admin-layout>
