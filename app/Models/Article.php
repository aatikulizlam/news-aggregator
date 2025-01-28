<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'author', 'source', 'url', 'content', 'category', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
