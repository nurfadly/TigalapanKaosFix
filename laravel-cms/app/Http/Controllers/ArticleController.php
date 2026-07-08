<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = Article::with('category')
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->q.'%'))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('type', 'article')->orderBy('name')->get();

        return view('articles.index', compact('articles', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'article')->orderBy('name')->get();

        return view('articles.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateArticle($request);

        $coverPath = $request->hasFile('cover_image')
            ? $request->file('cover_image')->store('articles', 'public')
            : null;

        Article::create([
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'cover_image' => $coverPath,
            'published_at' => $request->boolean('publish_now') ? now() : ($data['published_at'] ?? null),
        ]);

        return redirect()->route('articles.index')->with('status', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article): View
    {
        $categories = Category::where('type', 'article')->orderBy('name')->get();

        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validateArticle($request, $article->id);

        $coverPath = $article->cover_image;
        if ($request->hasFile('cover_image')) {
            if ($coverPath) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('articles', 'public');
        }

        $article->update([
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'slug' => $data['title'] !== $article->title ? $this->uniqueSlug($data['title'], $article->id) : $article->slug,
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'cover_image' => $coverPath,
            'published_at' => $request->boolean('publish_now') ? ($article->published_at ?? now()) : ($data['published_at'] ?? null),
        ]);

        return redirect()->route('articles.index')->with('status', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->cover_image) {
            Storage::disk('public')->delete($article->cover_image);
        }
        $article->delete();

        return redirect()->route('articles.index')->with('status', 'Artikel berhasil dihapus.');
    }

    private function validateArticle(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'body' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'published_at' => ['nullable', 'date'],
            'publish_now' => ['nullable', 'boolean'],
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Article::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
