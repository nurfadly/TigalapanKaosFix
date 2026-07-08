<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::withCount('items')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($sub) => $sub
                ->where('customer_name', 'like', '%'.$request->q.'%')
                ->orWhere('customer_phone', 'like', '%'.$request->q.'%')
            ))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,processing,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $order->update($data);

        return redirect()->route('orders.show', $order)->with('status', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('orders.index')->with('status', 'Pesanan berhasil dihapus.');
    }
}
