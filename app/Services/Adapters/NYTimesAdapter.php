<?php

namespace App\Services\Adapters;

use App\Interfaces\NewsSourceAdapter;
use GuzzleHttp\Client;

class NYTimesAdapter implements NewsSourceAdapter
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => config('services.nyt.uri')]);
    }

    public function fetchArticles(array $params): array
    {
        $query = [
            'q' => $params['q'] ?? null,
            'begin_date' => isset($params['from']) ? date('Ymd', strtotime($params['from'])) : null,
            'end_date' => isset($params['to']) ? date('Ymd', strtotime($params['to'])) : null,
            'api-key' => config('services.nyt.key'),
        ];


        $query = array_filter($query);

        $response = $this->client->get('search/v2/articlesearch.json', [
            'query' => $query,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['response']['docs'])) {
            return [];
        }


        return array_map(function ($article) {
            return [
                'title' => $article['headline']['main'],
                'author' => $article['byline']['original'] ?? null,
                'source' => 'New York Times',
                'url' => $article['web_url'],
                'content' => $article['lead_paragraph'] ?? '',
                'category' => null,
                'published_at' => $article['pub_date'],
            ];
        }, $data['response']['docs']);
    }
}
