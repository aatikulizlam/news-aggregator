<?php

use App\Jobs\FetchArticlesJob;
use Illuminate\Support\Facades\Schedule;

 Schedule::job(new FetchArticlesJob('newsapi'))->hourly();

 Schedule::job(new FetchArticlesJob('guardian'))->hourly();

 Schedule::job(new FetchArticlesJob('nyt'))->hourly();
