<?php

namespace App\Interfaces;

interface NewsSourceAdapter
{
    public function fetchArticles(array $params): array;
}
