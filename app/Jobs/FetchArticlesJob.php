<?php

namespace App\Jobs;

use App\Services\NewsService;

class FetchArticlesJob
{
    private $source;

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function handle(NewsService $newsService): void
    {
        try {
            $articles = $newsService->fetchArticlesFromSource($this->source);
        } catch (\Exception $e) {
            \Log::error("Error fetching articles from {$this->source}: " . $e->getMessage());
        }
    }
}
