<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Laravel\Vapor\Http\Controllers\SignedStorageUrlController as VaporSignedStorageUrlController;

class SignedStorageUrlController extends VaporSignedStorageUrlController
{
    public function store(Request $request)
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            Auth::shouldUse($guard);

            if (Auth::check()) {
                break;
            }
        }

        return parent::store($request);
    }
}
