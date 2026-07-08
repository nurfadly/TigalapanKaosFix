<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockCatalog;
use App\Models\StockImport;
use App\Models\StockItem;
use App\Services\StockImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    /**
     * Halaman utama: form upload + riwayat ringkasan tiap upload sebelumnya.
     */
    public function index(): View
    {
        $imports = StockImport::with('importer')->latest('created_at')->take(20)->get();
        $latest = $imports->first();

        return view('stock.index', compact('imports', 'latest'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:51200'],
        ]);

        try {
            $import = (new StockImportService())->import($request->file('file'), $request->user()->id);
        } catch (\Throwable $e) {
            return back()->with('status', 'Gagal memproses file: '.$e->getMessage());
        }

        return redirect()->route('stock.index')->with('status', sprintf(
            'Berhasil import %s baris, %s item unik, %s outlet dari file "%s".',
            number_format($import->total_rows, 0, ',', '.'),
            number_format($import->total_items, 0, ',', '.'),
            $import->total_outlets,
            $import->filename
        ));
    }

    /**
     * Halaman browse data stok saat ini (hasil import terakhir),
     * bisa difilter per outlet, kategori, dan pencarian nama item.
     */
    public function data(Request $request): View
    {
        $items = StockItem::with(['catalog.product'])
            ->when($request->filled('outlet'), fn ($q) => $q->where('outlet', $request->outlet))
            ->when($request->filled('q'), fn ($q) => $q->whereHas('catalog', fn ($c) => $c->where('raw_name', 'like', '%'.$request->q.'%')))
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('catalog', fn ($c) => $c->where('category', $request->kategori)))
            ->orderBy('outlet')
            ->paginate(50)
            ->withQueryString();

        $outlets = StockItem::select('outlet')->distinct()->orderBy('outlet')->pluck('outlet');
        $categories = StockCatalog::select('category')->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('stock.data', compact('items', 'outlets', 'categories'));
    }

    /**
     * Halaman review pencocokan: item hasil import yang belum tertaut ke Produk
     * katalog (atau skor pencocokan otomatisnya rendah) bisa dicocokkan manual
     * di sini. Sekali dicocokkan, berlaku untuk semua outlet karena mengacu
     * ke stock_catalog, bukan per baris stock_items.
     */
    public function matchIndex(Request $request): View
    {
        $status = $request->get('status', 'unmatched');

        $catalog = StockCatalog::query()
            ->when($status === 'unmatched', fn ($q) => $q->whereNull('matched_product_id'))
            ->when($status === 'matched', fn ($q) => $q->whereNotNull('matched_product_id'))
            ->when($request->filled('q'), fn ($q) => $q->where('raw_name', 'like', '%'.$request->q.'%'))
            ->orderBy('raw_name')
            ->paginate(50)
            ->withQueryString();

        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('stock.match', compact('catalog', 'products', 'status'));
    }

    public function matchUpdate(Request $request, StockCatalog $stockCatalog): RedirectResponse
    {
        $data = $request->validate([
            'matched_product_id' => ['nullable', 'exists:products,id'],
        ]);

        $stockCatalog->update([
            'matched_product_id' => $data['matched_product_id'] ?: null,
            'matched_manually' => true,
            'match_score' => null,
        ]);

        return back()->with('status', 'Pencocokan untuk "'.$stockCatalog->raw_name.'" berhasil disimpan.');
    }
}
