<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreakingNews;
use App\Models\Setting;
use Illuminate\Http\Request;

class BreakingNewsController extends Controller
{
    public function index()
    {
        $this->ensureAdministrator();

        return view('admin.breaking-news.index', [
            'items' => BreakingNews::orderBy('sort_order')->orderByDesc('id')->paginate(20),
            'label' => Setting::get('breaking_news_label', 'LE FIL INFO'),
        ]);
    }

    public function create()
    {
        $this->ensureAdministrator();

        return view('admin.breaking-news.form', ['item' => new BreakingNews]);
    }

    public function store(Request $request)
    {
        $this->ensureAdministrator();
        BreakingNews::create($this->validateItem($request));

        return redirect()->route('admin.breaking-news.index')->with('success', 'Message ajouté au fil info.');
    }

    public function edit(BreakingNews $breakingNews)
    {
        $this->ensureAdministrator();

        return view('admin.breaking-news.form', ['item' => $breakingNews]);
    }

    public function update(Request $request, BreakingNews $breakingNews)
    {
        $this->ensureAdministrator();
        $breakingNews->update($this->validateItem($request));

        return redirect()->route('admin.breaking-news.index')->with('success', 'Message du fil info mis à jour.');
    }

    public function destroy(BreakingNews $breakingNews)
    {
        $this->ensureAdministrator();
        $breakingNews->delete();

        return redirect()->route('admin.breaking-news.index')->with('success', 'Message supprimé du fil info.');
    }

    public function updateLabel(Request $request)
    {
        $this->ensureAdministrator();
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:30'],
        ]);
        Setting::set('breaking_news_label', $validated['label']);

        return redirect()->route('admin.breaking-news.index')->with('success', 'Badge du fil info mis à jour.');
    }

    private function validateItem(Request $request): array
    {
        return $request->validate([
            'text' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048', 'regex:/^https?:\\/\\//i'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
