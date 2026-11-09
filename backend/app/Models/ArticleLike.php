<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticleLike extends Model
{
    protected $fillable = [
        'visitor_token',
    ];

    public static function visitorToken(): string
    {
        $session = request()->session();
        $token = $session->get('article_like_visitor_token');

        if (! $token) {
            $token = hash('sha256', Str::random(64));
            $session->put('article_like_visitor_token', $token);
        }

        return $token;
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
