<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class NewsSourceController extends Controller
{
    public function index()
    {
        $this->ensureAdministrator();

        return view('admin.sources.index', [
            'sources' => NewsSource::with(['category', 'country'])->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        $this->ensureAdministrator();

        return view('admin.sources.form', [
            'source' => new NewsSource,
            'categories' => Category::active()->orderBy('name')->get(),
            'countries' => Country::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureAdministrator();
        NewsSource::create($this->validateSource($request));

        return redirect()->route('admin.sources.index')->with('success', 'Source enregistrée. Les articles importés restent des brouillons à vérifier.');
    }

    public function edit(NewsSource $source)
    {
        $this->ensureAdministrator();

        return view('admin.sources.form', [
            'source' => $source,
            'categories' => Category::active()->orderBy('name')->get(),
            'countries' => Country::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, NewsSource $source)
    {
        $this->ensureAdministrator();
        $source->update($this->validateSource($request, $source));

        return redirect()->route('admin.sources.index')->with('success', 'Source mise à jour.');
    }

    public function destroy(NewsSource $source)
    {
        $this->ensureAdministrator();
        $source->delete();

        return redirect()->route('admin.sources.index')->with('success', 'Source supprimée.');
    }

    public function import(NewsSource $source)
    {
        $this->ensureAdministrator();
        abort_unless($source->is_active && $source->is_trusted, 422, 'Activez et vérifiez la source avant de lancer un import.');

        $options = ['--source' => $source->id];
        if ($source->category_id) {
            $options['--category'] = $source->category->slug;
        }

        $exitCode = Artisan::call('rss:import', $options);
        if ($exitCode !== 0) {
            return redirect()->route('admin.sources.index')
                ->withErrors(['import' => trim(Artisan::output()) ?: 'L’import a échoué. Vérifiez le flux et les journaux.']);
        }

        $source->update(['last_imported_at' => now()]);

        return redirect()->route('admin.sources.index')->with('success', trim(Artisan::output()));
    }

    private function validateSource(Request $request, ?NewsSource $source = null): array
    {
        $feedUrlRule = ['required', 'url', 'max:512', 'regex:/^https?:\\/\\//i'];
        if ($source) {
            $feedUrlRule[] = 'unique:news_sources,feed_url,'.$source->id;
        } else {
            $feedUrlRule[] = 'unique:news_sources,feed_url';
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'feed_url' => $feedUrlRule,
            'website_url' => ['nullable', 'url', 'max:2048'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'is_trusted' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_trusted' => $request->boolean('is_trusted'),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
