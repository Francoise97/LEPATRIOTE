<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ArticleMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_attach_gallery_images_external_video_and_pdf_to_an_article(): void
    {
        Storage::fake('public');
        $administrator = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Actualité',
            'slug' => 'actualite',
            'is_active' => true,
        ]);

        $this->actingAs($administrator)
            ->post(route('admin.articles.store'), [
                'title' => 'Reportage multimédia',
                'content' => 'Contenu du reportage.',
                'category_id' => $category->id,
                'status' => 'published',
                'gallery_images' => [
                    UploadedFile::fake()->image('photo.jpg'),
                    UploadedFile::fake()->image('photo-2.jpg'),
                ],
                'video_files' => [UploadedFile::fake()->create('clip.mp4', 1, 'video/mp4')],
                'video_urls' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                'documents' => [UploadedFile::fake()->createWithContent('communique.pdf', "%PDF-1.4\nDocument de test")],
            ])
            ->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', 'reportage-multimedia')->firstOrFail();
        $this->assertSame(2, $article->media()->where('collection_name', 'gallery')->count());
        $this->assertSame(2, $article->media()->where('collection_name', 'video')->count());
        $this->assertSame(1, $article->media()->where('collection_name', 'document')->count());
        Storage::disk('public')->assertExists($article->media()->where('collection_name', 'gallery')->value('file_path'));
        Storage::disk('public')->assertExists($article->media()->where('file_name', 'clip.mp4')->value('file_path'));

        $this->get(route('admin.articles.edit', $article))
            ->assertOk()
            ->assertSee('Ajouter des vidéos')
            ->assertSee('youtube.com/watch?v=dQw4w9WgXcQ');

        $this->get(route('article.show', $article->slug))
            ->assertOk()
            ->assertSee('Galerie photos')
            ->assertSee('youtube-nocookie.com/embed/dQw4w9WgXcQ')
            ->assertSee('<video class="article-video" controls', false)
            ->assertSee('communique.pdf');

        $this->get('/')
            ->assertInertia(fn (Assert $page) => $page
                ->where('heroArticles.0.image', $article->media()->where('collection_name', 'gallery')->first()->publicUrl())
                ->has('heroArticles.0.images', 2)
                ->where('heroArticles.0.video.external', false)
                ->has('heroArticles.0.videos', 2)
            );

        $galleryMedia = $article->media()->where('collection_name', 'gallery')->firstOrFail();
        $this->actingAs($administrator)
            ->put(route('admin.articles.update', $article), [
                'title' => $article->title,
                'content' => $article->content,
                'category_id' => $category->id,
                'status' => 'published',
                'remove_media' => [$galleryMedia->id],
            ])
            ->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseMissing('media', ['id' => $galleryMedia->id]);
        Storage::disk('public')->assertMissing($galleryMedia->file_path);
    }

    public function test_admin_cannot_attach_video_from_an_unapproved_host(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Actualité',
            'slug' => 'actualite',
            'is_active' => true,
        ]);

        $this->actingAs($administrator)
            ->from(route('admin.articles.create'))
            ->post(route('admin.articles.store'), [
                'title' => 'Lien vidéo invalide',
                'content' => 'Contenu.',
                'category_id' => $category->id,
                'status' => 'draft',
                'video_urls' => ['https://example.com/video/123456'],
            ])
            ->assertRedirect(route('admin.articles.create'))
            ->assertSessionHasErrors('video_urls.0');

        $this->assertDatabaseMissing('articles', ['slug' => 'lien-video-invalide']);

        $this->from(route('admin.articles.create'))
            ->post(route('admin.articles.store'), [
                'title' => 'Lien vidéo mal formé',
                'content' => 'Contenu.',
                'category_id' => $category->id,
                'status' => 'draft',
                'video_urls' => [['not' => 'a URL']],
            ])
            ->assertRedirect(route('admin.articles.create'))
            ->assertSessionHasErrors('video_urls.0');

        $this->assertDatabaseMissing('articles', ['slug' => 'lien-video-mal-forme']);
    }

    public function test_video_embed_urls_accept_supported_youtube_and_vimeo_links_only(): void
    {
        $this->assertSame(
            'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
            (new Media(['file_type' => 'video/external', 'file_path' => 'https://youtu.be/dQw4w9WgXcQ']))->videoEmbedUrl()
        );
        $this->assertSame(
            'https://player.vimeo.com/video/12345678',
            (new Media(['file_type' => 'video/external', 'file_path' => 'https://vimeo.com/12345678']))->videoEmbedUrl()
        );
        $this->assertNull((new Media(['file_type' => 'video/external', 'file_path' => 'https://youtube.com.evil.test/watch?v=dQw4w9WgXcQ']))->videoEmbedUrl());
        $this->assertNull((new Media(['file_type' => 'video/external', 'file_path' => 'https://youtube.com/watch?v=bad']))->videoEmbedUrl());
        $this->assertNull((new Media(['file_type' => 'video/external', 'file_path' => '://']))->videoEmbedUrl());
    }
}
