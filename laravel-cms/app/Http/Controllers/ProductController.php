<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['category', 'images' => fn ($q) => $q->where('is_primary', true)])
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('type', 'product')->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'product')->orderBy('name')->get();
        $sizeOptions = Product::SIZE_OPTIONS;

        return view('products.create', compact('categories', 'sizeOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        $product = Product::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'discount_price' => $data['discount_price'] ?? null,
            'discount_start' => $data['discount_start'] ?? null,
            'discount_end' => $data['discount_end'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncColors($product, $data['colors'] ?? []);
        $this->syncSizes($product, $data['sizes'] ?? []);
        $this->storeImages($product, $request, $data['primary_index'] ?? null);

        return redirect()->route('products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $product->load(['colors', 'sizes', 'images' => fn ($q) => $q->orderBy('sort_order')]);
        $categories = Category::where('type', 'product')->orderBy('name')->get();
        $sizeOptions = Product::SIZE_OPTIONS;

        return view('products.edit', compact('product', 'categories', 'sizeOptions'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product->id);

        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['name'] !== $product->name ? $this->uniqueSlug($data['name'], $product->id) : $product->slug,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'discount_price' => $data['discount_price'] ?? null,
            'discount_start' => $data['discount_start'] ?? null,
            'discount_end' => $data['discount_end'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncColors($product, $data['colors'] ?? []);
        $this->syncSizes($product, $data['sizes'] ?? []);

        // Hapus gambar yang dicentang untuk dihapus
        if (!empty($data['delete_images'])) {
            $toDelete = $product->images()->whereIn('id', $data['delete_images'])->get();
            foreach ($toDelete as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        $this->storeImages($product, $request, $data['primary_index'] ?? null);

        // Kalau primary_index menunjuk ke gambar lama (bukan upload baru), set is_primary di situ
        if ($request->filled('primary_existing_id')) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $request->input('primary_existing_id'))->update(['is_primary' => true]);
        }

        return redirect()->route('products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'discount_start' => ['nullable', 'date'],
            'discount_end' => ['nullable', 'date', 'after_or_equal:discount_start'],
            'is_active' => ['nullable', 'boolean'],
            'colors' => ['nullable', 'array'],
            'colors.*.name' => ['required_with:colors', 'string', 'max:100'],
            'colors.*.hex' => ['required_with:colors', 'string', 'max:7'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'in:'.implode(',', Product::SIZE_OPTIONS)],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'primary_index' => ['nullable', 'integer'],
            'primary_existing_id' => ['nullable', 'integer', 'exists:product_images,id'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
        ]);
    }

    private function syncColors(Product $product, array $colors): void
    {
        $product->colors()->delete();
        foreach ($colors as $color) {
            if (empty($color['name']) || empty($color['hex'])) {
                continue;
            }
            $product->colors()->create([
                'name' => $color['name'],
                'hex' => Str::start($color['hex'], '#'),
            ]);
        }
    }

    private function syncSizes(Product $product, array $sizes): void
    {
        $product->sizes()->delete();
        foreach ($sizes as $size) {
            $product->sizes()->create(['size' => $size]);
        }
    }

    private function storeImages(Product $product, Request $request, ?int $primaryIndex): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $existingMax = (int) $product->images()->max('sort_order');
        $hasExistingPrimary = $product->images()->where('is_primary', true)->exists();
        $newlyCreated = [];

        foreach ($request->file('images') as $i => $file) {
            $path = $file->store('products', 'public');

            $newlyCreated[$i] = $product->images()->create([
                'path' => $path,
                'is_primary' => !$hasExistingPrimary && $i === 0,
                'sort_order' => $existingMax + $i + 1,
            ]);
        }

        // primary_index datang dari form khusus untuk gambar yang BARU diupload
        // (0 = file pertama yang dipilih di input file, dst).
        if ($primaryIndex !== null && isset($newlyCreated[$primaryIndex])) {
            $product->images()->update(['is_primary' => false]);
            $newlyCreated[$primaryIndex]->update(['is_primary' => true]);
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
