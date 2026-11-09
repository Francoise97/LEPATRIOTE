<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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
                ->has('breakingArticles', 1)
                ->has('categories', 1)
                ->where('categories.0.name', 'Politique')
                ->missing('heroArticles.1')
            );
    }

    public function test_home_carousel_prioritizes_published_articles_with_images(): void
    {
        $category = Category::create([
            'name' => 'Politique',
            'slug' => 'politique',
            'is_active' => true,
        ]);
        $author = User::factory()->create();

        Article::create([
            'title' => 'Une actualité récente sans image',
            'slug' => 'actualite-recente-sans-image',
            'content' => 'Contenu sans image.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Storage::disk('public')->put('uploads/carousel-feature.jpg', 'test-image');

        Article::create([
            'title' => 'Une actualité avec image',
            'slug' => 'actualite-avec-image',
            'content' => 'Contenu avec image.',
            'featured_image' => 'uploads/carousel-feature.jpg',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('heroArticles.0.title', 'Une actualité avec image')
                ->where('heroArticles.0.image', asset('storage/uploads/carousel-feature.jpg'))
                ->where('heroArticles.1.title', 'Une actualité récente sans image')
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

    public function test_home_page_can_filter_published_articles_by_country(): void
    {
        $category = Category::create([
            'name' => 'Politique',
            'slug' => 'politique',
            'is_active' => true,
        ]);
        $country = Country::create([
            'name' => 'Côte d’Ivoire',
            'code' => 'CI',
            'slug' => 'cote-d-ivoire',
            'is_active' => true,
        ]);
        $author = User::factory()->create();

        Article::create([
            'title' => 'Article en Côte d’Ivoire',
            'slug' => 'article-cote-ivoire',
            'content' => 'Actualité locale.',
            'category_id' => $category->id,
            'country_id' => $country->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);
        Article::create([
            'title' => 'Article sans pays',
            'slug' => 'article-sans-pays',
            'content' => 'Autre actualité.',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/?country='.$country->id)
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('selectedCountry', $country->id)
                ->has('heroArticles', 1)
                ->where('heroArticles.0.title', 'Article en Côte d’Ivoire')
            );
    }
}
