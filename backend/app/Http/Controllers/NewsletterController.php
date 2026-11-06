<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create([
            'email' => $validated['email'],
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Merci pour votre inscription à la newsletter !');
    }
}
