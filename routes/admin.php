<?php

Route::group(['namespace' => 'Admin', 'as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::get('', 'LoginController@create')->name('login.create');
    Route::post('', 'LoginController@store')->name('login.store');
    Route::get('get/state/{country_id_or_name}', 'StateController@getState')->name('get-state');
    Route::get('get/city/{state_id_or_name}', 'StateController@getCity')->name('get-city');

    Route::get('forgot-password', 'ForgotPasswordController@create')->name('forgot-password.create');
    Route::post('forgot-password/send-otp', 'ForgotPasswordController@sendOtp')->name('forgot-password.send-otp');
    Route::post('forgot-password', 'ForgotPasswordController@store')->name('forgot-password.store');
    Route::get('add-member', 'AdminController@addMember');

    Route::get('daily-income', function () {
        \App\Jobs\CalculateStakingIncome::dispatch(now());
    });

    Route::get('change-date', function (Illuminate\Http\Request $request) {
        \App\Models\StakingIncome::whereDate('created_at', now())->update(['created_at' => \Carbon\Carbon::parse($request->get('date'))]);
    });

    Route::group([
        'middleware' => ['adminAuth'],
    ], function () {
        Route::post('uploads/process', 'UploadController@process');

        Route::get('logout', 'LoginController@destroy')->name('login.destroy');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        Route::get('password/edit', 'PasswordController@edit')->name('password.edit');
        Route::post('password/update', 'PasswordController@update')->name('password.update');

        Route::get('toggle-theme', 'ToggleThemeController@update')->name('toggle-theme');

        Route::group([
            'prefix' => 'users',
            'as' => 'users.',
        ], function () {
            Route::get('', 'MemberController@index')->name('index')->middleware('permission:Members-read');
            Route::get('{member}/show', 'MemberController@show')->name('show')->middleware('permission:Members-read');
            Route::get('{user}/wallet-detail', 'MemberController@walletDetail')->name('wallet-detail');
            Route::get('{member}/edit', 'MemberController@edit')->name('edit')->middleware('permission:Members-update');
            Route::patch('{member}/update', 'MemberController@update')->name('update')->middleware('permission:Members-update');
            Route::get('{member}/log', 'MemberController@memberLog')->name('log')->middleware('permission:Members-read');

            Route::post('{member}/block', 'BlockMemberController@store')->name('block.store')->middleware('permission:Members-create');
            Route::delete('{member}/block', 'BlockMemberController@destroy')->name('block.destroy')->middleware('permission:Members-update');

            Route::get('{member}/change-password', 'ChangeMemberPasswordController@edit')->name('change-password.edit')->middleware('permission:Members-update');
            Route::patch('{member}/change-password', 'ChangeMemberPasswordController@update')->name('change-password.update')->middleware('permission:Members-update');
            Route::patch('{member}/transaction-change-password', 'ChangeMemberPasswordController@transactionChangePassword')->name('transaction-change-password.update')->middleware('permission:Members-update');

            Route::post('{member}/impersonate', 'MemberImpersonateController@store')
                ->name('impersonate.store')->middleware('permission:Members-read');

            Route::get('{member}/topup-power', 'MemberPowerController@topUpPower')->name('topup-power')->middleware('permission:Members-read');
            Route::post('{member}/topup-power', 'MemberPowerController@topUpPowerStore')->name('topup-power-store')->middleware('permission:Members-read');
            Route::get('{member}/stake-power', 'MemberPowerController@stakePower')->name('stake-power')->middleware('permission:Members-read');
            Route::post('{member}/stake-power', 'MemberPowerController@stakePowerStore')->name('stake-power-store')->middleware('permission:Members-read');

        });

        Route::group([
            'prefix' => 'admins',
            'as' => 'admins.',
        ], function () {
            Route::get('', 'AdminController@index')->name('index')->middleware('permission:Admins-read');
            Route::get('create', 'AdminController@create')->name('create')->middleware('permission:Admins-read');
            Route::post('store', 'AdminController@store')->name('store')->middleware('permission:Admins-create');
            Route::get('{admin}/edit', 'AdminController@edit')->name('edit')->middleware('permission:Admins-update');
            Route::post('{admin}/update', 'AdminController@update')->name('update')->middleware('permission:Admins-update');
            Route::get('{admin}/update-status', 'AdminController@updateStatus')->name('update-status')->middleware('permission:Admins-update');
            Route::get('{admin}/change-password', 'AdminController@changePassword')->name('change-password')->middleware('permission:Admins-update');
            Route::post('{admin}/change-password', 'AdminController@changePasswordUpdate')->name('change-password-update')->middleware('permission:Admins-update');
        });

        Route::group([
            'prefix' => 'packages',
            'as' => 'packages.',
        ], function () {
            Route::get('', 'PackageController@index')->name('index')->middleware('permission:Package-read');
            Route::get('create', 'PackageController@create')->name('create')->middleware('permission:Package-create');
            Route::post('', 'PackageController@store')->name('store')->middleware('permission:Package-create');
            Route::get('{package}/change-status', 'PackageController@changeStatus')->name('change-status')->middleware('permission:Package-update');
        });

        Route::get('genealogy/show/{code?}', 'GenealogyController@show')->name('genealogy.show')->middleware('permission:Genealogy Tree-read');
        Route::get('sponsor-genealogy/show/{code?}', 'SponsorGenealogyController@show')->name('sponsor-genealogy.show')->middleware('permission:Genealogy Tree-read');

        Route::group([
            'prefix' => 'buy-coins',
            'as' => 'buy-coins.',
        ], function () {
            Route::get('', 'BuyCoinController@index')->name('index');
        });

        Route::group([
            'prefix' => 'wallet-transactions',
            'as' => 'wallet-transactions.',
        ], function () {
            Route::get('', 'WalletTransactionController@index')->name('index')->middleware('permission:Wallet-read');
            Route::get('create', 'WalletTransactionController@create')->name('create')->middleware('permission:Wallet-create');
            Route::post('', 'WalletTransactionController@store')->name('store')->middleware('permission:Wallet-create');
        });

        Route::group([
            'prefix' => 'reports',
            'as' => 'reports.',
        ], function () {
            Route::get('top-earners', 'ReportController@topEarners')->name('top-earners')->middleware('permission:Reports-read');
            Route::get('top-up', 'ReportController@topUp')->name('top-up')->middleware('permission:Reports-read');
            Route::get('{topUp}/view-invoice', 'InvoiceController@show')->name('view-invoice')->middleware('permission:Reports-read');
            Route::get('most-active-member', 'ReportController@mostActiveMember')->name('most-active-user')->middleware('permission:Reports-read');
            Route::any('my-team', 'ReportController@level')->name('my-team');
            Route::get('level-detail/{walletAddress?}/{level?}', 'ReportController@memberLevelDetail')->name('level-detail');
            Route::any('royalty-reward-income-detail', 'ReportController@royaltyRewardIncomeDetail')->name('royalty-reward-income-detail')->middleware('permission:Reports-read');
            Route::get('admin-bv', 'ReportController@adminBv')->name('admin-bv');

        });

        Route::get('exports', 'ExportController@index')->name('exports.index')->middleware('permission:Exports-read');

        Route::get('contact-inquiries', 'ContactInquiryController@index')->name('contactInquires.index')->middleware('permission:Contact Inquiries-read');

        Route::group([
            'prefix' => 'withdrawal-requests',
            'as' => 'withdrawal-requests.',
        ], function () {
            Route::get('', 'WithdrawalController@index')->name('index');
            Route::post('{withdrawalRequest}/update', 'WithdrawalController@update')->name('update');
        });

        Route::group([
            'prefix' => 'support-ticket',
            'as' => 'support-ticket.',
        ], function () {
            Route::get('', 'SupportTicketController@get')->name('get')->middleware('permission:Support Ticket-read');
            Route::get('support-ticket-detail/{id}', 'SupportTicketController@getDetails')->name('details.get')->middleware('permission:Support Ticket-read');
            Route::post('send', 'SupportTicketController@store')->name('send')->middleware('permission:Support Ticket-update');
            Route::get('clear', 'SupportTicketController@clearAll')->name('clear')->middleware('permission:Support Ticket-delete');
        });

        Route::group([
            'prefix' => 'deposits',
            'as' => 'deposits.',
        ], function () {
            Route::get('', 'DepositController@index')->name('index')->middleware('permission:Deposits-read');
            Route::get('create', 'DepositController@create')->name('create')->middleware('permission:Deposits-update');
            Route::post('store', 'DepositController@store')->name('store')->middleware('permission:Deposits-update');
        });

        Route::group([
            'prefix' => 'websetting',
            'as' => 'websetting.',
        ], function () {});

        Route::group([
            'prefix' => 'settings',
            'as' => 'settings.',
        ], function () {
            Route::get('', 'SettingsController@index')->name('index');
            Route::patch('update', 'SettingsController@update')->name('update');
            Route::get('content', 'SettingsController@content')->name('content');
        });

        Route::group([
            'prefix' => 'daily-income-percentage',
            'as' => 'daily-income-percentage.',
        ], function () {
            Route::get('', 'DailyIncomePercentageController@index')->name('index');
            Route::patch('update', 'DailyIncomePercentageController@update')->name('update');
        });

        Route::group([
            'prefix' => 'faqs',
            'as' => 'faqs.',
        ], function () {
            Route::get('', 'FaqsController@index')->name('index');
            Route::get('create', 'FaqsController@create')->name('create');
            Route::post('store', 'FaqsController@store')->name('store');
            Route::get('{faq}/edit', 'FaqsController@edit')->name('edit');
            Route::any('{faq}/update', 'FaqsController@update')->name('update');
        });

        Route::group([
            'prefix' => 'business-plan',
            'as' => 'business-plan.',
        ], function () {
            Route::any('', 'BusinessPlanController@show')->name('show');
        });

        Route::any('direct-seller-contract', 'DashboardController@directSellerContract')->name('direct-seller-contract');

        Route::group([
            'prefix' => 'app-settings',
            'as' => 'app-settings.',
        ], function () {
            Route::get('', 'AppSettingsController@show')->name('show');
            Route::post('update', 'AppSettingsController@appSettingUpdate')->name('update');
            Route::post('apk-upload', 'AppSettingsController@apkUpload')->name('apk-upload');
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
            'prefix' => 'income-withdrawal-requests',
            'as' => 'income-withdrawal-requests.',
        ], function () {
            Route::get('', 'IncomeWithdrawalController@index')->name('index')->middleware('permission:Withdrawal Requests-read');
            Route::get('retry-transfer/{incomeWithdrawalRequest}', 'IncomeWithdrawalController@retryTransfer')->name('retry-transfer')->middleware('permission:Withdrawal Requests-update');
            Route::get('get-total', 'IncomeWithdrawalController@withdrawalTotal')->name('get-total');
            Route::post('status-update', 'IncomeWithdrawalController@statusChange')->name('status-update');
        });

        Route::group([
            'prefix' => 'company-wallet',
            'as' => 'company-wallet.',
        ], function () {
            Route::get('', 'CompanyWalletController@index')->name('index')->middleware('permission:Company Wallet-read');
            Route::get('create', 'CompanyWalletController@create')->name('create')->middleware('permission:Company Wallet-create');
            Route::post('store', 'CompanyWalletController@store')->name('store')->middleware('permission:Company Wallet-create');
            Route::get('{companyWallet}/update-status', 'CompanyWalletController@updateStatus')->name('update-status')->middleware('permission:Company Wallet-update');
            Route::get('update-locked-status/{companyWallet}', 'CompanyWalletController@updateLockedAt')->name('update-locked-status')->middleware('permission:Company Wallet-update');
        });

        Route::group([
            'prefix' => 'stake',
            'as' => 'stake.',
        ], function () {
            Route::get('', 'StakeController@index')->name('index');
            Route::get('create', 'StakeController@create')->name('create');
            Route::post('store', 'StakeController@store')->name('store');
        });

        Route::group([
            'prefix' => 'top-up',
            'as' => 'top-up.',
        ], function () {
            Route::get('create', 'TopUpController@create')->name('create');
            Route::post('store', 'TopUpController@store')->name('store');
        });

    });
});
