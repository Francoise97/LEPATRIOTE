<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SitePreviewController extends Controller
{
    public function __invoke()
    {
        return view('admin.preview', [
            'previewUrl' => route('home'),
        ]);
    }
}
