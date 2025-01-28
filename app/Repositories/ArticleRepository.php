<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleRepository
{
    public function getArticles(array $filters, int $perPage = 10)
    {
        $query = Article::query();

        if (!empty($filters['category'])) {
            $query->where('category',  $filters['category'])
            ->orWhere('category', Str::headline($filters['category']));
        }
        if (!empty($filters['source'])) {
            $query
            ->where('source', $filters['source'])
            ->orWhere('source', Str::headline($filters['source']));
        }
        if (!empty($filters['date'])) {
            $query->whereDate('published_at', $filters['date']);
        }

        return $query->paginate($perPage);
    }

    public function storeArticles(array $articles): void
    {
        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'] ?? 'Untitled',
                    'author' => $article['author'] ?? null,
                    'source' => $article['source'] ?? 'Unknown',
                    'content' => $article['content'] ?? '',
                    'category' => $article['category'] ?? 'general',
                    'published_at' => $article['published_at'] ?? now(),
                ]
            );
        }
    }
}
