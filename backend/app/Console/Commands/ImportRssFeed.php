<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SimplePie\SimplePie;

class ImportRssFeed extends Command
{
    protected $signature = 'rss:import {url} {--category= : Rubrique de secours (slug) si le flux ne fournit pas de rubrique reconnue}';

    protected $description = 'Importer les articles RSS en brouillon pour validation éditoriale';

    public function handle()
    {
        $url = $this->argument('url');
        $fallbackCategory = null;

        if ($this->option('category')) {
            $fallbackCategory = Category::active()
                ->where('slug', Str::slug($this->option('category')))
                ->first();

            if (! $fallbackCategory) {
                $this->error('La rubrique de secours indiquée est introuvable ou inactive.');

                return self::FAILURE;
            }
        }

        $author = User::query()
            ->where('is_active', true)
            ->whereIn('role', ['admin', 'editor'])
            ->orderBy('id')
            ->first();

        if (! $author) {
            $this->error('Aucun compte actif administrateur ou éditeur ne peut être attribué aux articles importés.');

            return self::FAILURE;
        }

        $this->info("Importing from: {$url}");

        $feed = new SimplePie;
        $feed->set_feed_url($url);
        $feed->enable_cache(false);
        $feed->init();

        if ($feed->error()) {
            $this->error('Error: '.$feed->error());

            return 1;
        }

        $count = 0;
        $skippedWithoutCategory = 0;

        foreach ($feed->get_items() as $item) {
            $title = trim((string) $item->get_title());
            $feedContent = $item->get_content() ?: $item->get_description();
            $content = trim(html_entity_decode(strip_tags((string) $feedContent), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $link = $item->get_permalink();
            $pubDate = $item->get_date();

            if ($title === '' || $content === '') {
                $this->line('Skipping an RSS item without a title or text.');

                continue;
            }

            $category = $this->resolveCategory($item, $fallbackCategory);

            if (! $category) {
                $skippedWithoutCategory++;
                $this->warn("Skipping (no matching category): {$title}");

                continue;
            }

            $slug = Str::slug($title);
            $existing = Article::where('slug', $slug)->first();

            if ($existing) {
                $this->line("Skipping (exists): {$title}");

                continue;
            }

            $description = trim(html_entity_decode(
                strip_tags((string) ($item->get_description() ?: $content)),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            ));

            Article::create([
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => Str::limit($description, 500),
                'category_id' => $category->id,
                'user_id' => $author->id,
                'source_url' => $link,
                'is_featured' => false,
                'is_breaking' => false,
                'status' => 'draft',
                'published_at' => null,
            ]);

            $count++;
            $this->line("Imported as draft in {$category->name}: {$title}");
        }

        $this->info("Imported {$count} articles as drafts.");

        if ($skippedWithoutCategory > 0) {
            $this->warn(
                "{$skippedWithoutCategory} item(s) were skipped because their RSS category does not match an active category. "
                .'Use --category=slug to choose a fallback category.'
            );
        }

        return self::SUCCESS;
    }

    private function resolveCategory($item, ?Category $fallbackCategory): ?Category
    {
        $categoryAliases = [
            'business' => 'economie',
            'economics' => 'economie',
            'economy' => 'economie',
            'culture' => 'culture',
            'international' => 'international',
            'politics' => 'politique',
            'politique' => 'politique',
            'society' => 'societe',
            'sports' => 'sport',
            'sport' => 'sport',
            'world' => 'international',
        ];

        foreach ($item->get_categories() ?? [] as $feedCategory) {
            $term = $feedCategory->get_label() ?: $feedCategory->get_term();

            if (! $term) {
                continue;
            }

            $slug = Str::slug($term);
            $slug = $categoryAliases[$slug] ?? $slug;

            $category = Category::active()->where('slug', $slug)->first();

            if ($category) {
                return $category;
            }
        }

        return $fallbackCategory;
    }
}
