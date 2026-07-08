<x-admin-layout title="Pesanan">

    <form method="GET" class="flex flex-wrap items-center gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / nomor telepon..." class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
        <select name="status" class="border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Status</option>
            @foreach (\App\Models\Order::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="border border-onyx/20 rounded-lg px-4 py-2 text-sm font-semibold hover:bg-onyx/5">Filter</button>
    </form>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Pemesan</th>
                    <th class="px-4 py-3 font-bold">Item</th>
                    <th class="px-4 py-3 font-bold">Total</th>
                    <th class="px-4 py-3 font-bold">Status</th>
                    <th class="px-4 py-3 font-bold">Waktu</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $order->customer_name }}</p>
                            <p class="text-onyx/50 text-xs">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="px-4 py-3 text-onyx/60">{{ $order->items_count }} barang</td>
                        <td class="px-4 py-3 font-semibold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @php
                                $badge = match($order->status) {
                                    'new' => 'bg-gusto/20 text-gusto',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-onyx/10 text-onyx/60',
                                };
                            @endphp
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $badge }}">{{ $order->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-onyx/50 text-xs">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-onyx/50">Belum ada pesanan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>

</x-admin-layout>
