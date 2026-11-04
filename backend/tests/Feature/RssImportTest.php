<?php

namespace Tests\Feature;

use App\Console\Commands\ImportRssFeed;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class RssImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_rss_feeds_are_scheduled_hourly(): void
    {
        $this->artisan('schedule:list')
            ->expectsOutputToContain('0 * * * *  php artisan rss:import https://www.togofirst.com/feed')
            ->expectsOutputToContain('0 * * * *  php artisan rss:import https://www.republicoftogo.com/feed')
            ->expectsOutputToContain('0 * * * *  php artisan rss:import https://www.lemonde.fr/rss/une.xml')
            ->assertExitCode(0);
    }

    public function test_rss_category_terms_are_mapped_to_active_categories(): void
    {
        $sport = Category::create([
            'name' => 'Sport',
            'slug' => 'sport',
            'is_active' => true,
        ]);
        $economy = Category::create([
            'name' => 'Économie',
            'slug' => 'economie',
            'is_active' => true,
        ]);

        $feedItem = new class
        {
            public function get_categories(): array
            {
                return [
                    new class
                    {
                        public function get_label(): string
                        {
                            return 'Sports';
                        }

                        public function get_term(): string
                        {
                            return 'Sports';
                        }
                    },
                ];
            }
        };

        $command = new ImportRssFeed;
        $resolver = new ReflectionMethod($command, 'resolveCategory');
        $resolver->setAccessible(true);

        $this->assertTrue($sport->is($resolver->invoke($command, $feedItem, null)));

        $businessItem = new class
        {
            public function get_categories(): array
            {
                return [
                    new class
                    {
                        public function get_label(): string
                        {
                            return 'Business';
                        }

                        public function get_term(): string
                        {
                            return 'Business';
                        }
                    },
                ];
            }
        };

        $this->assertTrue($economy->is($resolver->invoke($command, $businessItem, null)));
    }

    public function test_rss_import_rejects_an_unknown_fallback_category(): void
    {
        $this->artisan('rss:import', [
            'url' => 'https://example.com/feed.xml',
            '--category' => 'inconnue',
        ])
            ->assertExitCode(1)
            ->expectsOutputToContain('La rubrique de secours indiquée est introuvable ou inactive.');
    }
}
