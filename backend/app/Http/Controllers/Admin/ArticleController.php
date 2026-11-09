<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Country;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'author', 'country'])
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $tags = Tag::all();

        return view('admin.articles.create', compact('categories', 'countries', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateArticle($request);

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
            'country_id' => $validated['country_id'] ?? null,
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

        $this->storeAttachments($article, $validated);

        return redirect()->route('admin.articles.index')->with('success', 'Article créé avec succès');
    }

    public function edit(Article $article)
    {
        $article->load(['tags', 'media']);
        $categories = Category::all();
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $tags = Tag::all();

        return view('admin.articles.edit', compact('article', 'categories', 'countries', 'tags'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $this->validateArticle($request);
        $removeMedia = $request->validate([
            'remove_media' => ['sometimes', 'array'],
            'remove_media.*' => ['integer'],
        ])['remove_media'] ?? [];
        $mediaToRemove = $article->media()->whereIn('id', $removeMedia)->get();

        if ($mediaToRemove->count() !== count(array_unique($removeMedia))) {
            throw ValidationException::withMessages([
                'remove_media' => 'Un ou plusieurs médias sélectionnés ne sont pas associés à cet article.',
            ]);
        }

        foreach ($mediaToRemove as $media) {
            if ($media->file_type !== 'video/external') {
                Storage::disk('public')->delete($media->file_path);
            }
            $media->delete();
        }

        $imagePath = $article->featured_image;
        if ($request->boolean('remove_featured_image') && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }
        if ($request->hasFile('featured_image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
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
            'country_id' => $validated['country_id'] ?? null,
            'featured_image' => $imagePath,
            'is_featured' => $validated['is_featured'] ?? false,
            'is_breaking' => $validated['is_breaking'] ?? false,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' && ! $article->published_at ? now() : $article->published_at,
        ]);

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        $this->storeAttachments($article, $validated);

        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour avec succès');
    }

    public function destroy(Article $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        foreach ($article->media as $media) {
            if ($media->file_type !== 'video/external') {
                Storage::disk('public')->delete($media->file_path);
            }
        }
        $article->media()->delete();
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article supprimé avec succès');
    }

    private function validateArticle(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'category_id' => ['required', 'exists:categories,id'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
            'gallery_images' => ['sometimes', 'array', 'max:10'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
            'video_files' => ['sometimes', 'array', 'max:3'],
            'video_files.*' => ['file', 'mimetypes:video/mp4,video/webm,video/quicktime,video/ogg', 'max:102400'],
            'video_urls' => ['sometimes', 'array', 'max:5'],
            'video_urls.*' => ['nullable', 'url', 'max:2048'],
            'documents' => ['sometimes', 'array', 'max:10'],
            'documents.*' => ['file', 'mimes:pdf', 'max:10240'],
            'is_featured' => ['boolean'],
            'is_breaking' => ['boolean'],
            'status' => ['required', 'in:draft,published'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $videoUrls = $request->input('video_urls', []);
            foreach (is_array($videoUrls) ? $videoUrls : [] as $index => $url) {
                if (is_string($url) && $url !== '' && ! $this->isSupportedVideoUrl($url)) {
                    $validator->errors()->add("video_urls.$index", 'Utilisez un lien YouTube ou Vimeo valide.');
                }
            }
        });

        return $validator->validate();
    }

    private function isSupportedVideoUrl(string $url): bool
    {
        $parts = parse_url($url);
        if (! is_array($parts)) {
            return false;
        }
        $host = strtolower($parts['host'] ?? '');

        if (in_array($host, ['youtu.be', 'youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            return (new Media([
                'file_type' => 'video/external',
                'file_path' => $url,
            ]))->videoEmbedUrl() !== null;
        }

        return in_array($host, ['vimeo.com', 'www.vimeo.com'], true)
            && (new Media([
                'file_type' => 'video/external',
                'file_path' => $url,
            ]))->videoEmbedUrl() !== null;
    }

    private function storeAttachments(Article $article, array $validated): void
    {
        foreach ($validated['gallery_images'] ?? [] as $file) {
            $this->storeUploadedMedia($article, $file, 'gallery');
        }

        foreach ($validated['video_files'] ?? [] as $file) {
            $this->storeUploadedMedia($article, $file, 'video');
        }

        foreach ($validated['documents'] ?? [] as $file) {
            $this->storeUploadedMedia($article, $file, 'document');
        }

        foreach (array_filter($validated['video_urls'] ?? []) as $url) {
            $article->media()->create([
                'file_name' => parse_url($url, PHP_URL_HOST) ?: 'Vidéo externe',
                'file_path' => $url,
                'file_type' => 'video/external',
                'file_size' => 0,
                'collection_name' => 'video',
            ]);
        }
    }

    private function storeUploadedMedia(Article $article, UploadedFile $file, string $collection): void
    {
        $path = $file->store('uploads/'.$collection, 'public');

        $article->media()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType() ?: 'application/octet-stream',
            'file_size' => $file->getSize() ?: 0,
            'collection_name' => $collection,
        ]);
    }
}
