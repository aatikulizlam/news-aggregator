<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Repositories\ArticleRepository;
use App\Services\Adapters\NewsAPIAdapter;
use App\Services\Adapters\GuardianAdapter;
use App\Services\Adapters\NYTimesAdapter;

class NewsService
{
    private ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchArticlesFromSource(string $source): array
    {
        $adapter = $this->getAdapter($source);
        $articles = $adapter->fetchArticles([]);

        $this->repository->storeArticles($articles);

        return $articles;
    }

    private function getAdapter(string $source)
    {
        return match ($source) {
            'newsapi' => new NewsAPIAdapter(),
            'guardian' => new GuardianAdapter(),
            'nyt' => new NYTimesAdapter(),
            default => throw new \Exception("Invalid news source: {$source}"),
        };
    }
}
