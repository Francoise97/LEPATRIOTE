<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'articles' => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'drafts' => Article::where('status', 'draft')->count(),
            'categories' => Category::count(),
            'views' => (int) Article::sum('views_count'),
        ];

        $recentArticles = Article::with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentArticles'));
    }
}
