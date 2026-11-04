<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicArticlePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_page_displays_published_content_and_editorial_details(): void
    {
        $category = Category::create([
            'name' => 'Économie',
            'slug' => 'economie',
            'is_active' => true,
        ]);
        $author = User::factory()->create([
            'name' => 'Awa Journaliste',
            'bio' => 'Journaliste économie',
        ]);
        $article = Article::create([
            'title' => 'Un article avec son texte complet',
            'slug' => 'article-texte-complet',
            'content' => '<p>Le texte intégral de cet article est enregistré dans la base.</p>',
            'excerpt' => 'Le résumé éditorial de l’article.',
            'source_url' => 'https://example.com/source',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);
        $tag = Tag::create([
            'name' => 'Entrepreneuriat',
            'slug' => 'entrepreneuriat',
        ]);
        $article->tags()->attach($tag);

        Comment::create([
            'article_id' => $article->id,
            'author_name' => 'Lecteur approuvé',
            'author_email' => 'lecteur@example.com',
            'content' => 'Ce commentaire est visible.',
            'status' => 'approved',
        ]);
        Comment::create([
            'article_id' => $article->id,
            'author_name' => 'Lecteur en attente',
            'author_email' => 'attente@example.com',
            'content' => 'Ce commentaire attend une modération.',
            'status' => 'pending',
        ]);

        Article::create([
            'title' => 'Un article associé',
            'slug' => 'article-associe',
            'content' => 'Contenu associé.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('article.show', $article->slug))
            ->assertOk()
            ->assertSee('Un article avec son texte complet')
            ->assertSee('Le résumé éditorial de l’article.')
            ->assertSee('Le texte intégral de cet article est enregistré dans la base.')
            ->assertSee('Awa Journaliste')
            ->assertSee('Journaliste économie')
            ->assertSee('Entrepreneuriat')
            ->assertSee('https://example.com/source')
            ->assertSee('Lecteur approuvé')
            ->assertDontSee('Lecteur en attente')
            ->assertSee('Un article associé')
            ->assertSee('Partager l’article');
    }

    public function test_unpublished_and_not_yet_published_articles_are_not_publicly_accessible(): void
    {
        $category = Category::create([
            'name' => 'Politique',
            'slug' => 'politique',
            'is_active' => true,
        ]);
        $author = User::factory()->create();

        $article = Article::create([
            'title' => 'Un article programmé',
            'slug' => 'article-programme',
            'content' => 'Contenu non encore publié.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('article.show', $article->slug))->assertNotFound();
    }
}
