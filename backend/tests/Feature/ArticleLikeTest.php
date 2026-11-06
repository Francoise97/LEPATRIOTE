<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_like_and_unlike_a_published_article_once_per_session(): void
    {
        $article = $this->createArticle('published');

        $this->postJson(route('article.like', $article))
            ->assertOk()
            ->assertExactJson(['liked' => true, 'likes' => 1]);

        $this->postJson(route('article.like', $article))
            ->assertOk()
            ->assertExactJson(['liked' => false, 'likes' => 0]);

        $this->assertDatabaseCount('article_likes', 0);
    }

    public function test_a_draft_article_cannot_be_liked(): void
    {
        $article = $this->createArticle('draft');

        $this->postJson(route('article.like', $article))->assertNotFound();
        $this->assertDatabaseCount('article_likes', 0);
    }

    private function createArticle(string $status): Article
    {
        $category = Category::create([
            'name' => 'Politique',
            'slug' => 'politique',
            'is_active' => true,
        ]);
        $author = User::factory()->create();

        return Article::create([
            'title' => 'Article test',
            'slug' => 'article-test',
            'content' => 'Texte de test',
            'category_id' => $category->id,
            'user_id' => $author->id,
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
        ]);
    }
}
