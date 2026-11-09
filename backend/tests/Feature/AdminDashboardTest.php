<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_editorial_statistics_and_recent_articles(): void
    {
        $user = User::factory()->create(['name' => 'Aminata', 'role' => 'editor']);
        $category = Category::create([
            'name' => 'Politique',
            'slug' => 'politique',
            'is_active' => true,
        ]);

        Article::create([
            'title' => 'Une actualité récente',
            'slug' => 'actualite-recente',
            'content' => 'Contenu de test.',
            'category_id' => $category->id,
            'user_id' => $user->id,
            'status' => 'published',
            'views_count' => 42,
            'published_at' => now(),
        ]);
        Article::create([
            'title' => 'Un brouillon de rédaction',
            'slug' => 'brouillon-redaction',
            'content' => 'Contenu en attente.',
            'category_id' => $category->id,
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Bonjour Aminata.')
            ->assertSee('Tous les articles')
            ->assertSee('Publiés')
            ->assertSee('En brouillon')
            ->assertSee('Rubriques')
            ->assertSee('Une actualité récente')
            ->assertSee('Un brouillon de rédaction')
            ->assertSee('Écrire un article')
            ->assertSee('42', false);
    }

    public function test_dashboard_is_only_available_to_authenticated_users(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }
}
