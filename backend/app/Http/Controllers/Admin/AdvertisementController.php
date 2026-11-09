<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $this->ensureAdministrator();

        return view('admin.advertisements.index', [
            'advertisements' => Advertisement::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        $this->ensureAdministrator();

        return view('admin.advertisements.form', ['advertisement' => new Advertisement]);
    }

    public function store(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $this->validateAdvertisement($request);
        $validated['image_path'] = $validated['image']->store('advertisements', 'public');
        unset($validated['image']);
        Advertisement::create($validated);

        return redirect()->route('admin.advertisements.index')->with('success', 'Publicité créée.');
    }

    public function edit(Advertisement $advertisement)
    {
        $this->ensureAdministrator();

        return view('admin.advertisements.form', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
        $this->ensureAdministrator();

        $validated = $this->validateAdvertisement($request, false);
        if ($request->hasFile('image')) {
            $oldImage = $advertisement->image_path;
            $validated['image_path'] = $validated['image']->store('advertisements', 'public');
            Storage::disk('public')->delete($oldImage);
        }
        unset($validated['image']);
        $advertisement->update($validated);

        return redirect()->route('admin.advertisements.index')->with('success', 'Publicité mise à jour.');
    }

    public function destroy(Advertisement $advertisement)
    {
        $this->ensureAdministrator();
        Storage::disk('public')->delete($advertisement->image_path);
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('success', 'Publicité supprimée.');
    }

    private function validateAdvertisement(Request $request, bool $requireImage = true): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'target_url' => ['required', 'url', 'max:2048', 'regex:/^https?:\\/\\//i'],
            'placement' => ['required', 'in:home,sidebar,footer'],
            'image' => [$requireImage ? 'required' : 'nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
