<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{
    public function edit()
    {
        $this->ensureAdministrator();

        return view('admin.settings.edit', [
            'settings' => [
                'brand_slogan' => Setting::get('brand_slogan', 'L’information qui engage'),
                'brand_font' => Setting::get('brand_font', 'serif'),
                'primary_color' => Setting::get('primary_color', '#102b4e'),
                'accent_color' => Setting::get('accent_color', '#a51d35'),
                'logo' => Setting::get('logo'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'brand_slogan' => ['required', 'string', 'max:120'],
            'brand_font' => ['required', 'in:serif,sans'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);

        Setting::set('brand_slogan', $validated['brand_slogan']);
        Setting::set('brand_font', $validated['brand_font']);
        Setting::set('primary_color', $validated['primary_color']);
        Setting::set('accent_color', $validated['accent_color']);

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            $logoPath = $request->file('logo')->store('branding', 'public');
            Setting::set('logo', $logoPath);

            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
        }

        return redirect()->route('admin.settings.edit')->with('success', 'L’apparence du site a été mise à jour.');
    }

    private function ensureAdministrator(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
