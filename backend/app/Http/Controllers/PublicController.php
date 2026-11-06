<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ])['q'] ?? '');
        $searchArticles = fn () => $this->publishedArticles()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            }));

        $heroArticles = $searchArticles()
            ->with(['category', 'author'])
            ->orderByDesc('is_featured')
            ->latest()
            ->take(3)
            ->get();
        $heroArticleIds = $heroArticles->modelKeys();

        $latestArticles = $searchArticles()
            ->with('category')
            ->when($search === '' && $heroArticleIds !== [], fn ($query) => $query->whereNotIn('id', $heroArticleIds))
            ->latest()
            ->take(6)
            ->get();

        $relatedArticles = $heroArticleIds === []
            ? collect()
            : $searchArticles()
                ->with('category')
                ->where('category_id', $heroArticles->first()->category_id)
                ->whereNotIn('id', $heroArticleIds)
                ->orderByDesc('views_count')
                ->latest()
                ->take(3)
                ->get();

        if ($relatedArticles->count() < 3) {
            $relatedArticles = $relatedArticles->concat(
                $searchArticles()
                    ->with('category')
                    ->when($heroArticleIds !== [], fn ($query) => $query->whereNotIn('id', $heroArticleIds))
                    ->whereNotIn('id', $relatedArticles->pluck('id')->all())
                    ->orderByDesc('views_count')
                    ->latest()
                    ->take(3 - $relatedArticles->count())
                    ->get()
            );
        }

        $breakingArticles = $searchArticles()
            ->with('category')
            ->where('is_breaking', true)
            ->latest()
            ->take(5)
            ->get();

        $categories = Category::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return Inertia::render('Welcome', [
            'heroArticles' => $heroArticles->map(fn (Article $article) => $this->articleForFrontend($article)),
            'relatedArticles' => $relatedArticles->map(fn (Article $article) => $this->articleForFrontend($article)),
            'latestArticles' => $latestArticles->map(fn (Article $article) => $this->articleForFrontend($article)),
            'breakingArticles' => $breakingArticles->map(fn (Article $article) => $this->articleForFrontend($article)),
            'categories' => $categories->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'url' => route('category.show', $category->slug),
            ]),
            'search' => $search,
            'csrfToken' => csrf_token(),
            'homeUrl' => route('home'),
            'newsletterUrl' => route('newsletter.subscribe'),
            'adminUrl' => route('dashboard'),
            'currentDate' => now()->locale('fr')->translatedFormat('l d F Y'),
        ]);
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

    private function articleForFrontend(Article $article): array
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'excerpt' => $article->excerpt ?: Str::limit(strip_tags($article->content), 180),
            'image' => $article->featured_image ? asset('storage/'.$article->featured_image) : null,
            'category' => $article->category?->name ?? 'Actualité',
            'categoryUrl' => $article->category ? route('category.show', $article->category->slug) : null,
            'url' => route('article.show', $article->slug),
            'publishedAt' => ($article->published_at ?? $article->created_at)->toIso8601String(),
            'views' => (int) $article->views_count,
        ];
    }
}
