<x-admin-layout title="Detail Pesanan #{{ $order->id }}">

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-xl border border-onyx/10 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-onyx/5 text-left">
                    <tr>
                        <th class="px-4 py-3 font-bold">Produk</th>
                        <th class="px-4 py-3 font-bold">Varian</th>
                        <th class="px-4 py-3 font-bold">Harga</th>
                        <th class="px-4 py-3 font-bold">Qty</th>
                        <th class="px-4 py-3 font-bold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-onyx/10">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold">
                                {{ $item->product_name }}
                                @if (!$item->product_id)
                                    <span class="ml-1 text-xs text-onyx/40">(produk sudah tidak ada di katalog)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-onyx/60">{{ $item->variant ?: '-' }}</td>
                            <td class="px-4 py-3 text-onyx/60">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-onyx/60">{{ $item->qty }}</td>
                            <td class="px-4 py-3 text-right font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-onyx/10">
                        <td colspan="4" class="px-4 py-3 font-bold text-right">Total</td>
                        <td class="px-4 py-3 font-black text-right">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-onyx/10 p-5">
                <p class="text-xs font-bold uppercase text-onyx/50">Pemesan</p>
                <p class="mt-2 font-bold">{{ $order->customer_name }}</p>
                <p class="text-sm text-onyx/60">{{ $order->customer_phone }}</p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-gusto">
                    <i class="ph-bold ph-whatsapp-logo"></i> Chat WhatsApp
                </a>
                <p class="mt-3 text-xs text-onyx/40">Dipesan {{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>

            <form action="{{ route('orders.update', $order) }}" method="POST" class="bg-white rounded-xl border border-onyx/10 p-5">
                @csrf
                @method('PUT')
                <label class="text-sm font-bold">Status Pesanan</label>
                <select name="status" class="mt-2 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
                    @foreach (\App\Models\Order::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <label class="text-sm font-bold mt-4 block">Catatan Internal</label>
                <textarea name="notes" rows="3" class="mt-2 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">{{ old('notes', $order->notes) }}</textarea>
                <button type="submit" class="mt-4 w-full bg-onyx text-cloud font-bold px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan</button>
            </form>

            <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full text-sm font-semibold text-red-600 hover:text-red-800 py-2">Hapus Pesanan</button>
            </form>
        </div>
    </div>

</x-admin-layout>
