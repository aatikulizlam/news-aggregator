<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'source', 'date']);
        $perPage = $request->get('perPage', 10);

        $articles = $this->repository->getArticles($filters, $perPage);

        return response()->json($articles);
    }
}
