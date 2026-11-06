<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\BreakingNews;
use App\Models\Country;
use App\Models\NewsSource;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AdminSiteFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_update_site_appearance(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->actingAs($administrator)
            ->put(route('admin.settings.update'), [
                'brand_slogan' => 'Une information utile',
                'brand_font' => 'serif',
                'primary_color' => '#123456',
                'accent_color' => '#abcdef',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->assertSame('Une information utile', Setting::get('brand_slogan'));
        $this->assertSame('#123456', Setting::get('primary_color'));

        $this->get('/')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('siteAppearance.slogan', 'Une information utile')
                ->where('siteAppearance.primaryColor', '#123456')
                ->where('breakingLabel', 'LE FIL INFO')
            );
    }

    public function test_administrators_manage_ordered_active_breaking_news(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $first = BreakingNews::create([
            'text' => 'Premier message',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        BreakingNews::create([
            'text' => 'Message désactivé',
            'sort_order' => 1,
            'is_active' => false,
        ]);

        $this->actingAs($administrator)
            ->get(route('admin.breaking-news.index'))
            ->assertOk()
            ->assertSee('Premier message')
            ->assertSee('Message désactivé');

        $this->put(route('admin.breaking-news.update', $first), [
            'text' => 'Message modifié',
            'url' => 'https://example.com/news',
            'sort_order' => 1,
            'is_active' => '1',
        ])->assertRedirect(route('admin.breaking-news.index'));

        $this->put(route('admin.breaking-news.label'), ['label' => 'ACTUALITÉ'])
            ->assertRedirect(route('admin.breaking-news.index'));

        $this->get('/')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('breakingLabel', 'ACTUALITÉ')
                ->has('breakingItems', 1)
                ->where('breakingItems.0.text', 'Message modifié')
                ->where('breakingItems.0.url', 'https://example.com/news')
            );
    }

    public function test_non_administrators_cannot_manage_breaking_news(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $this->actingAs($author)
            ->get(route('admin.breaking-news.index'))
            ->assertForbidden();
    }

    public function test_non_administrators_cannot_access_site_settings(): void
    {
        $author = User::factory()->create(['role' => 'author']);

        $this->actingAs($author)
            ->get(route('admin.settings.edit'))
            ->assertForbidden();
    }

    public function test_subscribers_cannot_access_editorial_administration(): void
    {
        $subscriber = User::factory()->create(['role' => 'subscriber']);

        $this->actingAs($subscriber)
            ->get(route('dashboard'))
            ->assertForbidden();

        $this->get(route('admin.articles.index'))
            ->assertForbidden();
    }

    public function test_an_administrator_can_open_the_new_admin_sections(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->actingAs($administrator)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Typographie du slogan');

        $this->get(route('admin.sources.index'))
            ->assertOk()
            ->assertSee('Sources d’information');

        $this->get(route('admin.sources.create'))
            ->assertOk()
            ->assertSee('URL du flux RSS');

        $this->get(route('admin.advertisements.index'))
            ->assertOk()
            ->assertSee('Publicités');

        $this->get(route('admin.advertisements.create'))
            ->assertOk()
            ->assertSee('Lien de destination');

        $this->get(route('admin.breaking-news.index'))
            ->assertOk()
            ->assertSee('Texte du badge fixe');
    }

    public function test_news_source_import_is_blocked_until_the_source_is_verified(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $country = Country::create([
            'name' => 'Côte d’Ivoire',
            'code' => 'CI',
            'slug' => 'cote-d-ivoire',
            'is_active' => true,
        ]);
        $source = NewsSource::create([
            'name' => 'Source de test',
            'feed_url' => 'https://example.com/feed.xml',
            'country_id' => $country->id,
            'is_trusted' => false,
            'is_active' => true,
        ]);

        $this->actingAs($administrator)
            ->post(route('admin.sources.import', $source))
            ->assertUnprocessable();
    }

    public function test_only_currently_active_home_ads_are_sent_to_the_frontend(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        Advertisement::create([
            'title' => 'Publicité active',
            'image_path' => 'advertisements/active.jpg',
            'target_url' => 'https://example.com/active',
            'placement' => 'home',
            'is_active' => true,
        ]);
        Advertisement::create([
            'title' => 'Publicité désactivée',
            'image_path' => 'advertisements/inactive.jpg',
            'target_url' => 'https://example.com/inactive',
            'placement' => 'home',
            'is_active' => false,
        ]);

        $this->get('/')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('advertisements', 1)
                ->where('advertisements.0.title', 'Publicité active')
            );
    }
}
