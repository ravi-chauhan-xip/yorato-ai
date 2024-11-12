<?php

Route::group([
    'middleware' => 'bindings',
], function () {
    Route::group([
        'prefix' => 'member',
        'namespace' => 'Member\Api',
        'middleware' => ['guard:api'],
    ], function () {
        Route::get('app-status', function () {
            return response()->json([
                'maintenance' => env('MOBILE_APP_MAINTENANCE', false),
                'update' => request('appVersion') < env('ANDROID_APP_VERSION'),
            ]);
        });

        Route::get('login-background', 'LoginController@loginBackground');
        Route::post('login', 'LoginController@store');
        Route::put('forgot-password', 'ForgotPasswordController@update');
        Route::post('register', 'RegisterController@create');

        Route::post('members', 'LoginController@memberList');

        Route::group([
            'middleware' => ['auth:api'],
        ], function () {
            Route::get('dashboard', 'DashboardController@index');
            Route::get('member-balance', 'DashboardController@balance');
            Route::get('banking/details', 'DashboardController@bankDetails');
            //        Route::get('invoice/download', 'InvoiceController@downloadInvoice');

            Route::group(['prefix' => 'profile'], function () {
                Route::get('', 'ProfileController@show');
                Route::post('update', 'ProfileController@update');
                Route::post('change-password', 'ProfileController@changePassword');
                Route::get('get-login-member', 'ProfileController@getLoginMember');
                Route::get('KYCController@show');
                Route::post('KYCController@store');

            });

            Route::group(['prefix' => 'pin'], function () {
                Route::get('', 'PinController@index');
                Route::post('member-detail', 'PinController@memberDetail');
                Route::post('pin-transfer', 'PinController@pinTransfer');
            });

            Route::group(['prefix' => 'pin-requests'], function () {
                Route::get('', 'PinRequestController@index');
                Route::get('create', 'PinRequestController@create');
                Route::post('', 'PinRequestController@store');
            });

            Route::group(['prefix' => 'wallet-transaction'], function () {
                Route::get('', 'WalletTransactionController@index');
            });

            Route::group([
                'prefix' => 'payouts',
                'as' => 'payouts.',
            ], function () {
                Route::get('', 'PayoutController@index');
            });
            Route::group([
                'prefix' => 'reports',
                'as' => 'reports.',
            ], function () {
                Route::get('direct', 'ReportController@myDirect');
                Route::get('downline', 'ReportController@myDownline');
                Route::get('tds-report', 'ReportController@tds');
            });

            Route::group([
                'prefix' => 'bank',
                'as' => 'bank.',
            ], function () {
                Route::get('index', 'DashboardController@bankDetails');
            });

            Route::group([
                'prefix' => 'top-ups',
                'as' => 'top-ups.',
            ], function () {
                Route::get('', 'TopUpController@index')->name('index');
                Route::post('store', 'TopUpController@store')->name('store');
            });

            Route::post('vapor/signed-storage-url', 'SignedStorageUrlController@store');

        });
    });
});
