<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BreakingNewsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NewsSourceController;
use App\Http\Controllers\Admin\SitePreviewController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\ArticleLikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/article/{slug}', [PublicController::class, 'show'])->name('article.show');
Route::post('/article/{slug}/comment', [CommentController::class, 'store'])->name('article.comment');
Route::post('/article/{article}/like', [ArticleLikeController::class, 'toggle'])->middleware('throttle:30,1')->name('article.like');
Route::get('/category/{slug}', [PublicController::class, 'category'])->name('category.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'editor'])
    ->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/preview', SitePreviewController::class)->name('preview');
    Route::get('/settings', [SiteSettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SiteSettingsController::class, 'update'])->name('settings.update');
    Route::put('/breaking-news/label', [BreakingNewsController::class, 'updateLabel'])->name('breaking-news.label');
    Route::resource('breaking-news', BreakingNewsController::class)
        ->parameters(['breaking-news' => 'breakingNews'])
        ->except(['show']);
    Route::resource('advertisements', AdvertisementController::class)->except(['show']);
    Route::post('/sources/{source}/import', [NewsSourceController::class, 'import'])->name('sources.import');
    Route::resource('sources', NewsSourceController::class)->except(['show']);

    Route::prefix('articles')->name('articles.')->middleware('editor')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('categories')->name('categories.')->middleware('editor')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
