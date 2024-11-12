<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\RedirectResponse;

class ToggleThemeController extends Controller
{
    public function update(): RedirectResponse
    {
        Auth::user()->update([
            'is_dark_theme' => ! Auth::user()->is_dark_theme,
        ]);

        return redirect()->back();
    }
}
