<?php

namespace App\Services\Adapters;

use App\Interfaces\NewsSourceAdapter;
use GuzzleHttp\Client;

class NewsAPIAdapter implements NewsSourceAdapter
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => config('services.newsapi.uri')]);
    }

    public function fetchArticles(array $params): array
    {
        //TODO:: Need to fetch sources from the API and store them in the database. Use the sources array below to fetch the sources.
        $sources = ["abc-news","abc-news-au","aftenposten","al-jazeera-english","ansa","argaam","ars-technica","ary-news","associated-press","australian-financial-review","axios","bbc-news","bbc-sport","bild","blasting-news-br","bleacher-report","bloomberg","breitbart-news","business-insider","buzzfeed","cbc-news","cbs-news","cnn","cnn-es","crypto-coins-news","der-tagesspiegel","die-zeit","el-mundo","engadget","entertainment-weekly","espn","espn-cric-info","financial-post","focus","football-italia","fortune","four-four-two","fox-news","fox-sports","globo","google-news","google-news-ar","google-news-au","google-news-br","google-news-ca","google-news-fr","google-news-in","google-news-is","google-news-it","google-news-ru","google-news-sa","google-news-uk","goteborgs-posten","gruenderszene","hacker-news","handelsblatt","ign","il-sole-24-ore","independent","infobae","info-money","la-gaceta","la-nacion","la-repubblica","le-monde","lenta","lequipe","les-echos","liberation","marca","mashable","medical-news-today","msnbc","mtv-news","mtv-news-uk","national-geographic","national-review","nbc-news","news24","new-scientist","news-com-au","newsweek","new-york-magazine","next-big-future","nfl-news","nhl-news","nrk","politico","polygon","rbc","recode","reddit-r-all","reuters","rt","rte","rtl-nieuws","sabq","spiegel-online","svenska-dagbladet","t3n","talksport","techcrunch","techcrunch-cn","techradar","the-american-conservative","the-globe-and-mail","the-hill","the-hindu","the-huffington-post","the-irish-times","the-jerusalem-post","the-lad-bible","the-next-web","the-sport-bible","the-times-of-india","the-verge","the-wall-street-journal","the-washington-post","the-washington-times","time","usa-today","vice-news","wired","wired-de","wirtschafts-woche","xinhua-net","ynet"];

        $response = $this->client->get('everything', [
            'query' => [
                'q' => $params['q'] ?? 'latest news',
                'from' => $params['from'] ?? now()->subDays(1)->toDateString(),
                'to' => $params['to'] ?? now()->toDateString(),
                'sources' => implode(',', $sources),
                'apiKey' => config('services.newsapi.key'),
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.newsapi.key'),
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'author' => $article['author'] ?? null,
                'source' => $article['source']['name'],
                'url' => $article['url'],
                'content' => $article['content'] ?? '',
                'category' => $article['category'] ?? 'general',
                'published_at' => $article['publishedAt'],
            ];
        }, $data['articles']);
    }
}
