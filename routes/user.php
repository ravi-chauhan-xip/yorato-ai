<?php

Route::group([
    'namespace' => 'Member',
    'as' => 'user.',
    'prefix' => 'user',
], function () {
    Route::get('', 'LoginController@create')->name('login.create');
    Route::post('login', 'LoginController@store')->name('login.store');

    Route::get('get-referral-wallet', 'RegisterController@getReferralWallet')->name('get-referral-wallet');
    Route::get('register', 'RegisterController@create')->name('register.create');
    Route::post('register', 'RegisterController@store')->name('register.store');
    Route::get('get-packages', 'TopUpController@getPackages')->name('get-packages');

    Route::get('forgot-password', 'ForgotPasswordController@create')->name('forgot-password.create');
    Route::post('forgot-password', 'ForgotPasswordController@store')->name('forgot-password.store');

    Route::get('check-wallet-address/{walletAddress}', 'LoginController@checkWalletAddress');

    Route::group([
        'middleware' => ['memberAuth'],
    ], function () {
        Route::get('logout', 'LoginController@destroy')->name('login.destroy');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::get('members/{member}/show', 'MemberController@show')->name('members.show');
        Route::get('id-card', 'IDCardController@show')->name('id-card');

        Route::get('toggle-theme', 'ToggleThemeController@update')->name('toggle-theme');

        Route::group([
            'prefix' => 'profile',
            'as' => 'profile.',
        ], function () {
            Route::get('', 'ProfileController@show')->name('show');
            Route::post('update', 'ProfileController@update')->name('update');
        });

        Route::group(['prefix' => 'genealogy', 'as' => 'genealogy.'], function () {
            Route::get('{member?}', 'GenealogyController@show')->name('show');
        });

        Route::group(['prefix' => 'sponsor-genealogy', 'as' => 'sponsor-genealogy.'], function () {
            Route::get('{member?}', 'SponsorGenealogyController@sponsorShow')->name('show');
        });

        Route::group([
            'prefix' => 'deposits',
            'as' => 'deposits.',
        ], function () {
            Route::get('', 'DepositController@index')->name('index');
            Route::any('create', 'DepositController@create')->name('create');
        });

        Route::get('wallet-transactions', 'WalletTransactionController@index')->name('wallet-transactions.index');

        Route::group([
            'prefix' => 'top-up',
            'as' => 'top-up.',
        ], function () {
            Route::get('', 'TopUpController@index')->name('show');
            Route::get('create', 'TopUpController@create')->name('create');
            Route::post('store-validation', 'TopUpController@storeValidation')->name('store-validation');
            Route::post('store', 'TopUpController@store')->name('store');
            Route::get('calculation', 'TopUpController@calculation')->name('calculation');
            Route::get('wallet-create', 'TopUpController@walletCreate')->name('wallet-create');
            Route::post('store-wallet-create', 'TopUpController@walletStore')->name('wallet-store');
        });

        Route::group([
            'prefix' => 'reports',
            'as' => 'reports.',
        ], function () {
            Route::get('direct', 'ReportController@direct')->name('direct');
            Route::get('downline', 'ReportController@myDownline')->name('downline');
            Route::get('my-team', 'ReportController@level')->name('my-team');
            Route::get('level-detail/{level?}', 'ReportController@memberLevelDetail')->name('level-detail');
            Route::get('downline-left', 'ReportController@downLineLeft')->name('downline-left');
            Route::get('downline-right', 'ReportController@downLineRight')->name('downline-right');
            Route::get('admin-power', 'ReportController@adminPower')->name('admin-power');
        });

        Route::get('exports', 'ExportController@index')->name('exports.index');

        //        Route::get('banks', 'BankController@index')->name('banks.index');
        Route::post('support-tickets', 'SupportTicketController@store')->name('support-tickets.store');
        Route::any('support', 'SupportTicketController@index')->name('support-tickets.index');

        Route::group([
            'prefix' => 'support',
            'as' => 'support.',
        ], function () {
            Route::get('', 'SupportTicketController@index')->name('index');
            Route::get('create', 'SupportTicketController@create')->name('create');
            Route::post('', 'SupportTicketController@store')->name('store');
            Route::get('{id}/ticket', 'SupportTicketController@ticket')->name('ticket');
            Route::post('{id}/ticket-message', 'SupportTicketController@ticketMessage')->name('ticketMessage');
        });

        Route::group([
            'prefix' => 'incomes',
            'as' => 'incomes.',
        ], function () {
            Route::get('staking-income', 'IncomeController@stakingIncome')->name('staking-income');
            Route::get('direct-wallet-income', 'IncomeController@directWalletIncome')->name('direct-wallet-income');
            Route::get('direct-sponsor-stake-income', 'IncomeController@directSponsorStakeIncome')->name('direct-sponsor-stake-income');
            Route::get('team-matching-wallet-income', 'IncomeController@teamMatchingWalletIncome')->name('team-matching-wallet-income');
            Route::get('team-matching-staking-income', 'IncomeController@teamMatchingStakingIncome')->name('team-matching-staking-income');
            Route::get('leadership-income', 'IncomeController@leadershipIncome')->name('leadership-income');
        });

        Route::group([
            'prefix' => 'stake',
            'as' => 'stake.',
        ], function () {
            Route::get('', 'StakeController@index')->name('show');
            Route::get('create', 'StakeController@create')->name('create');
            Route::post('store', 'StakeController@store')->name('store');
            Route::get('calculation', 'StakeController@calculation')->name('calculation');
            Route::post('store-validation', 'StakeController@storeValidation')->name('store-validation');
            Route::get('wallet-create', 'StakeController@walletCreate')->name('wallet-create');
            Route::post('wallet-store', 'StakeController@walletStore')->name('wallet-store');
        });

        Route::group([
            'prefix' => 'income-withdrawals',
            'as' => 'income-withdrawals.',
        ], function () {
            Route::get('', 'IncomeWithdrawalController@index')->name('index');
            Route::get('create', 'IncomeWithdrawalController@create')->name('create');
            Route::post('store', 'IncomeWithdrawalController@store')->name('store');
            Route::get('calculation', 'IncomeWithdrawalController@calculation')->name('calculation');
        });
    });
});
