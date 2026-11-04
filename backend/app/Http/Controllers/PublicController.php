<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class PublicController extends Controller
{
    public function index()
    {
        $heroArticle = $this->publishedArticles()
            ->with(['category', 'author'])
            ->orderByDesc('is_featured')
            ->latest()
            ->first();

        $latestArticles = $this->publishedArticles()
            ->with('category')
            ->when($heroArticle, fn ($query) => $query->whereKeyNot($heroArticle->getKey()))
            ->latest()
            ->take(6)
            ->get();

        $breakingArticles = $this->publishedArticles()
            ->with('category')
            ->where('is_breaking', true)
            ->latest()
            ->take(5)
            ->get();

        $popularArticles = $this->publishedArticles()
            ->with('category')
            ->orderByDesc('views_count')
            ->latest()
            ->take(5)
            ->get();

        $categories = Category::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('public.home', compact(
            'heroArticle',
            'latestArticles',
            'breakingArticles',
            'popularArticles',
            'categories'
        ));
    }

    public function show($slug)
    {
        $article = $this->publishedArticles()
            ->where('slug', $slug)
            ->with([
                'category',
                'author',
                'tags',
                'comments' => fn ($query) => $query->approved()->latest(),
            ])
            ->firstOrFail();

        $relatedArticles = $this->publishedArticles()
            ->with('category')
            ->where('category_id', $article->category_id)
            ->whereKeyNot($article->getKey())
            ->latest()
            ->take(3)
            ->get();

        $categories = Category::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $wordCount = count(preg_split(
            '/\s+/u',
            trim(strip_tags($article->content)),
            -1,
            PREG_SPLIT_NO_EMPTY
        ) ?: []);
        $readingTime = $article->reading_time ?: max(1, (int) ceil($wordCount / 220));

        return view('public.article', compact('article', 'relatedArticles', 'categories', 'readingTime'));
    }

    public function category($slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();
        $articles = $this->publishedArticles()
            ->with(['category', 'author'])
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(10);

        $categories = Category::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('public.category', compact('category', 'articles', 'categories'));
    }

    private function publishedArticles()
    {
        return Article::query()
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
