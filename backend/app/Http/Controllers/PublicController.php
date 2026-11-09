<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\BreakingNews;
use App\Models\Category;
use App\Models\Country;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'integer', 'exists:countries,id'],
        ]);
        $search = trim($filters['q'] ?? '');
        $countryId = $filters['country'] ?? null;
        $searchArticles = fn () => $this->publishedArticles()
            ->when($countryId, fn ($query) => $query->where('country_id', $countryId))
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            }));

        $heroArticles = $searchArticles()
            ->with(['category', 'author', 'country', 'media'])
            ->where(function ($query) {
                $query->where('is_featured', true)
                    ->orWhereNotNull('featured_image')
                    ->orWhereHas('media', fn ($query) => $query->whereIn('collection_name', ['gallery', 'video']));
            })
            ->orderByDesc('is_featured')
            ->orderByRaw("CASE WHEN featured_image IS NULL OR featured_image = '' THEN 1 ELSE 0 END")
            ->latest()
            ->get();
        if ($heroArticles->count() < 3) {
            $heroArticles = $heroArticles->concat(
                $searchArticles()
                    ->with(['category', 'author', 'country', 'media'])
                    ->whereNotIn('id', $heroArticles->modelKeys())
                    ->latest()
                    ->take(3 - $heroArticles->count())
                    ->get()
            );
        }
        $heroArticleIds = $heroArticles->modelKeys();

        $latestArticles = $searchArticles()
            ->with(['category', 'country', 'media'])
            ->when($search === '' && $heroArticleIds !== [], fn ($query) => $query->whereNotIn('id', $heroArticleIds))
            ->latest()
            ->take(6)
            ->get();

        $relatedArticles = $heroArticleIds === []
            ? collect()
            : $searchArticles()
                ->with(['category', 'country', 'media'])
                ->where('category_id', $heroArticles->first()->category_id)
                ->whereNotIn('id', $heroArticleIds)
                ->orderByDesc('views_count')
                ->latest()
                ->take(3)
                ->get();

        if ($relatedArticles->count() < 3) {
            $relatedArticles = $relatedArticles->concat(
                $searchArticles()
                    ->with(['category', 'country', 'media'])
                    ->when($heroArticleIds !== [], fn ($query) => $query->whereNotIn('id', $heroArticleIds))
                    ->whereNotIn('id', $relatedArticles->pluck('id')->all())
                    ->orderByDesc('views_count')
                    ->latest()
                    ->take(3 - $relatedArticles->count())
                    ->get()
            );
        }

        $breakingItems = BreakingNews::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->take(12)
            ->get(['id', 'text', 'url']);

        $categories = Category::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $advertisements = Advertisement::currentlyVisible()
            ->orderBy('sort_order')
            ->latest()
            ->get()
            ->map(fn (Advertisement $advertisement) => [
                'title' => $advertisement->title,
                'image' => asset('storage/'.$advertisement->image_path),
                'url' => $advertisement->target_url,
                'placement' => $advertisement->placement,
            ]);
        $articleIds = $heroArticles->modelKeys();
        $articleIds = array_merge($articleIds, $relatedArticles->pluck('id')->all(), $latestArticles->pluck('id')->all());
        $visitorToken = ArticleLike::visitorToken();
        $likedArticleIds = ArticleLike::query()
            ->where('visitor_token', $visitorToken)
            ->whereIn('article_id', $articleIds)
            ->pluck('article_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return Inertia::render('Welcome', [
            'heroArticles' => $heroArticles->map(fn (Article $article) => $this->articleForFrontend($article, $likedArticleIds)),
            'relatedArticles' => $relatedArticles->map(fn (Article $article) => $this->articleForFrontend($article, $likedArticleIds)),
            'latestArticles' => $latestArticles->map(fn (Article $article) => $this->articleForFrontend($article, $likedArticleIds)),
            'breakingItems' => $breakingItems,
            'breakingLabel' => Setting::get('breaking_news_label', 'LE FIL INFO'),
            'categories' => $categories->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'url' => route('category.show', $category->slug),
            ]),
            'countries' => $countries->map(fn (Country $country) => [
                'id' => $country->id,
                'name' => $country->name,
            ]),
            'selectedCountry' => $countryId ? (int) $countryId : null,
            'advertisements' => $advertisements,
            'search' => $search,
            'csrfToken' => csrf_token(),
            'homeUrl' => route('home'),
            'newsletterUrl' => route('newsletter.subscribe'),
            'adminUrl' => route('dashboard'),
            'currentDate' => now()->locale('fr')->translatedFormat('l d F Y'),
            'siteAppearance' => [
                'logo' => Setting::get('logo') ? asset('storage/'.Setting::get('logo')) : asset('images/logo.png'),
                'slogan' => Setting::get('brand_slogan', 'L’information qui engage'),
                'font' => Setting::get('brand_font', 'serif'),
                'primaryColor' => Setting::get('primary_color', '#102b4e'),
                'accentColor' => Setting::get('accent_color', '#a51d35'),
            ],
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
                'media',
                'comments' => fn ($query) => $query->approved()->latest(),
            ])
            ->firstOrFail();

        $relatedArticles = $this->publishedArticles()
            ->with(['category', 'media'])
            ->where('category_id', $article->category_id)
            ->whereKeyNot($article->getKey())
            ->latest()
            ->take(3)
            ->get();

        $categories = Category::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get();
        $countries = Country::where('is_active', true)->orderBy('name')->get();

        $wordCount = count(preg_split(
            '/\s+/u',
            trim(strip_tags($article->content)),
            -1,
            PREG_SPLIT_NO_EMPTY
        ) ?: []);
        $readingTime = $article->reading_time ?: max(1, (int) ceil($wordCount / 220));

        return view('public.article', compact('article', 'relatedArticles', 'categories', 'countries', 'readingTime'));
    }

    public function category($slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();
        $articles = $this->publishedArticles()
            ->with(['category', 'author', 'media'])
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

    private function articleForFrontend(Article $article, array $likedArticleIds = []): array
    {
        $images = collect([
            $article->featured_image ? asset('storage/'.$article->featured_image) : null,
        ])->concat(
            $article->media
                ->where('collection_name', 'gallery')
                ->map(fn (Media $media) => $media->publicUrl())
        )->filter()->values();
        $videos = $article->media
            ->where('collection_name', 'video')
            ->map(fn (Media $media) => [
                'url' => $media->publicUrl(),
                'embedUrl' => $media->videoEmbedUrl(),
                'external' => $media->file_type === 'video/external',
            ])
            ->values();

        return [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'excerpt' => $article->excerpt ?: Str::limit(strip_tags($article->content), 180),
            'image' => $images->first(),
            'images' => $images,
            'video' => $videos->first(),
            'videos' => $videos,
            'category' => $article->category?->name ?? 'Actualité',
            'categoryUrl' => $article->category ? route('category.show', $article->category->slug) : null,
            'country' => $article->country?->name,
            'url' => route('article.show', $article->slug),
            'likeUrl' => route('article.like', $article),
            'publishedAt' => ($article->published_at ?? $article->created_at)->toIso8601String(),
            'views' => (int) $article->views_count,
            'likes' => (int) $article->likes_count,
            'liked' => in_array($article->id, $likedArticleIds, true),
        ];
    }
}
