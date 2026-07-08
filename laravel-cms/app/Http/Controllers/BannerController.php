<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('sort_order')->get();

        return view('banners.index', compact('banners'));
    }

    public function create(): View
    {
        $nextOrder = (int) Banner::max('sort_order') + 1;

        return view('banners.create', compact('nextOrder'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateBanner($request, imageRequired: true);

        $data['image'] = $request->file('image')->store('banners', 'public');
        $data['is_active'] = $request->boolean('is_active');

        Banner::create($data);

        return redirect()->route('banners.index')->with('status', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner): View
    {
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $this->validateBanner($request, imageRequired: false);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        $banner->update($data);

        return redirect()->route('banners.index')->with('status', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        Storage::disk('public')->delete($banner->image);
        $banner->delete();

        return redirect()->route('banners.index')->with('status', 'Banner berhasil dihapus.');
    }

    private function validateBanner(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:300'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:4096'],
            'cta_text' => ['nullable', 'string', 'max:50'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
