<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'author'])
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'source_url' => 'nullable|url|max:2048',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'status' => 'required|in:draft,published',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('uploads', 'public');
        }

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => \Str::slug($validated['title']),
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'featured_image' => $imagePath,
            'is_featured' => $validated['is_featured'] ?? false,
            'is_breaking' => $validated['is_breaking'] ?? false,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        if (isset($validated['tags'])) {
            $article->tags()->attach($validated['tags']);
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article créé avec succès');
    }

    public function edit(Article $article)
    {
        $article->load('tags');
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'source_url' => 'nullable|url|max:2048',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'status' => 'required|in:draft,published',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $imagePath = $article->featured_image;
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('uploads', 'public');
        }

        $article->update([
            'title' => $validated['title'],
            'slug' => \Str::slug($validated['title']),
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
            'category_id' => $validated['category_id'],
            'featured_image' => $imagePath,
            'is_featured' => $validated['is_featured'] ?? false,
            'is_breaking' => $validated['is_breaking'] ?? false,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' && ! $article->published_at ? now() : $article->published_at,
        ]);

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour avec succès');
    }

    public function destroy(Article $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article supprimé avec succès');
    }
}
