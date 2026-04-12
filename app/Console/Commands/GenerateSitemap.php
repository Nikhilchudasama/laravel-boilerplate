<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     */
    protected $description = 'Generate sitemap.xml for the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Add static pages
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/login', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/register', 'priority' => '0.8', 'changefreq' => 'monthly'],
        ];

        foreach ($pages as $page) {
            $sitemap .= $this->generateUrlEntry(
                config('app.url') . $page['url'],
                now()->toIso8601String(),
                $page['changefreq'],
                $page['priority']
            );
        }

        $sitemap .= '</urlset>';

        // Save sitemap to public directory
        $path = public_path('sitemap.xml');
        File::put($path, $sitemap);

        $this->info('Sitemap generated successfully at: ' . $path);

        return Command::SUCCESS;
    }

    /**
     * Generate a URL entry for the sitemap.
     */
    protected function generateUrlEntry(string $url, string $lastmod, string $changefreq, string $priority): string
    {
        return sprintf(
            '  <url>%s    <loc>%s</loc>%s    <lastmod>%s</lastmod>%s    <changefreq>%s</changefreq>%s    <priority>%s</priority>%s  </url>%s',
            PHP_EOL,
            htmlspecialchars($url),
            PHP_EOL,
            $lastmod,
            PHP_EOL,
            $changefreq,
            PHP_EOL,
            $priority,
            PHP_EOL,
            PHP_EOL
        );
    }
}
