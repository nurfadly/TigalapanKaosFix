<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();

        return view('testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        $nextOrder = (int) Testimonial::max('sort_order') + 1;

        return view('testimonials.create', compact('nextOrder'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateTestimonial($request);
        $data['is_active'] = $request->boolean('is_active');

        Testimonial::create($data);

        return redirect()->route('testimonials.index')->with('status', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validateTestimonial($request);
        $data['is_active'] = $request->boolean('is_active');

        $testimonial->update($data);

        return redirect()->route('testimonials.index')->with('status', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('testimonials.index')->with('status', 'Testimoni berhasil dihapus.');
    }

    private function validateTestimonial(Request $request): array
    {
        return $request->validate([
            'quote' => ['required', 'string'],
            'author_name' => ['required', 'string', 'max:255'],
            'author_title' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }
}
