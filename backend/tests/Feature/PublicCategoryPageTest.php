<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCategoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_page_lists_published_articles_and_links_to_their_detail_pages(): void
    {
        $category = Category::create([
            'name' => 'Économie',
            'slug' => 'economie',
            'description' => 'Actualité économique.',
            'is_active' => true,
        ]);
        $author = User::factory()->create(['name' => 'Awa Journaliste']);

        Article::create([
            'title' => 'Un article économique publié',
            'slug' => 'article-economique-publie',
            'content' => 'Contenu complet de la nouvelle.',
            'excerpt' => 'Résumé de la nouvelle.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Article::create([
            'title' => 'Un article économique en brouillon',
            'slug' => 'article-economique-brouillon',
            'content' => 'Contenu privé.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'draft',
        ]);

        Article::create([
            'title' => 'Un article économique programmé',
            'slug' => 'article-economique-programme',
            'content' => 'Contenu pas encore publié.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('category.show', $category->slug))
            ->assertOk()
            ->assertSee('Actualité économique.')
            ->assertSee('Un article économique publié')
            ->assertSee('Résumé de la nouvelle.')
            ->assertSee('Awa Journaliste')
            ->assertSee(route('article.show', 'article-economique-publie'))
            ->assertDontSee('Un article économique en brouillon')
            ->assertDontSee('Un article économique programmé');
    }

    public function test_inactive_categories_are_not_publicly_accessible(): void
    {
        $category = Category::create([
            'name' => 'Rubrique masquée',
            'slug' => 'rubrique-masquee',
            'is_active' => false,
        ]);

        $this->get(route('category.show', $category->slug))->assertNotFound();
    }
}
