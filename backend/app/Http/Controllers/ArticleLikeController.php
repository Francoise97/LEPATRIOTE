<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ArticleLikeController extends Controller
{
    public function toggle(Article $article): JsonResponse
    {
        abort_unless($article->status === 'published' && (! $article->published_at || $article->published_at->isPast()), 404);

        $visitorToken = ArticleLike::visitorToken();
        $liked = DB::transaction(function () use ($article, $visitorToken) {
            $lockedArticle = Article::query()->lockForUpdate()->findOrFail($article->id);
            $existingLike = $lockedArticle->likes()
                ->where('visitor_token', $visitorToken)
                ->first();

            if ($existingLike) {
                $existingLike->delete();
                $lockedArticle->decrement('likes_count');

                return false;
            }

            $lockedArticle->likes()->create(['visitor_token' => $visitorToken]);
            $lockedArticle->increment('likes_count');

            return true;
        });

        return response()->json([
            'liked' => $liked,
            'likes' => (int) $article->fresh()->likes_count,
        ]);
    }
}
