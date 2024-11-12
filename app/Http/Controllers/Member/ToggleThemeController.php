<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Auth;

class ToggleThemeController extends Controller
{
    public function update()
    {
        Auth::user()->update([
            'is_dark_theme' => ! Auth::user()->is_dark_theme,
        ]);

        return redirect()->back();
    }
}
