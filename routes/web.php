<?php

use App\Models\City;
use App\Models\Member;
use App\Models\Pin;
use App\Models\State;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Website',
    'as' => 'website.',
], function () {
    Route::any('/', 'HomeController@index')->name('home');
    Route::any('contact', 'HomeController@contact')->name('contact');
    Route::get('about', 'HomeController@about')->name('about');
    Route::get('terms', 'HomeController@terms')->name('terms');
    Route::get('privacy-policy', 'HomeController@privacyPolicy')->name('privacy-policy');
});

Route::get('member/{member}/name', function (Member $member) {
    return response()->json([
        'user' => [
            'name' => optional($member->user)->name,
        ],
    ]);
})->name('members.show');

Route::get('pin/{code}/name', function ($code = null) {
    return response()->json([
        'pin' => Pin::with('package')->whereCode($code)->first(),
    ]);
})->name('packageName');

Route::get('district/{state_id}', function ($state_id) {
    $districts = City::where('state_id', $state_id)->get();

    return response()->json($districts);
})->name('district.show');

Route::get('state', function () {
    return response()->json([
        'states' => State::with('cities')->get(),
    ]);
})->name('state');

Route::get('city/{state_id?}', function (Illuminate\Http\Request $request) {
    if ($request->get('state_id')) {
        $city = City::where('state_id', $request->get('state_id'))->get();
    } else {
        $city = City::get();
    }

    return response()->json(['cities' => $city]);
})->name('city');
