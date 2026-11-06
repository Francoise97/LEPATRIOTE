<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicHomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_published_featured_articles_and_active_categories(): void
    {
        $category = Category::create([
            'name' => 'Politique',
            'slug' => 'politique',
            'is_active' => true,
        ]);
        $author = User::factory()->create();

        Article::create([
            'title' => 'Un article à la une',
            'slug' => 'article-a-la-une',
            'content' => 'Le contenu de cet article.',
            'excerpt' => 'Un résumé de l’article.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        Article::create([
            'title' => 'Un article en brouillon',
            'slug' => 'article-en-brouillon',
            'content' => 'Ce contenu ne doit pas être public.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'draft',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->has('heroArticles', 1)
                ->where('heroArticles.0.title', 'Un article à la une')
                ->where('heroArticles.0.excerpt', 'Un résumé de l’article.')
                ->has('categories', 1)
                ->where('categories.0.name', 'Politique')
                ->missing('heroArticles.1')
            );
    }

    public function test_home_page_shows_an_empty_state_when_there_are_no_published_articles(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->has('heroArticles', 0)
                ->has('latestArticles', 0)
            );
    }
}
