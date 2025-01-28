<?php

namespace App\Services\Adapters;

use App\Interfaces\NewsSourceAdapter;
use GuzzleHttp\Client;

class GuardianAdapter implements NewsSourceAdapter
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => config('services.guardian.uri')]);
    }

    public function fetchArticles(array $params): array
    {
        $query = [
            'q' => $params['q'] ?? null,
            'section' => $params['category'] ?? null,
            'from-date' => $params['from'] ?? null,
            'to-date' => $params['to'] ?? null,
            'api-key' => config('services.guardian.key'),
        ];

        $query = array_filter($query);

        $response = $this->client->get('search', [
            'query' => $query,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['response']['results'])) {
            return [];
        }

        return array_map(function ($article) {
            return [
                'title' => $article['webTitle'],
                'author' => null,
                'source' => 'The Guardian',
                'url' => $article['webUrl'],
                'content' => null,
                'category' => $article['sectionName'] ?? 'general',
                'published_at' => $article['webPublicationDate'],
            ];
        }, $data['response']['results']);
    }
}
