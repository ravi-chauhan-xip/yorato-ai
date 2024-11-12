<?php

namespace App\Providers;

use App\Http\Controllers\SignedStorageUrlController;
use App\Library\Settings;
use App\Models\Export;
use App\Models\Member;
use App\Models\WalletTransaction;
use App\Observers\ExportObserver;
use App\Observers\MemberObserver;
use App\Observers\WalletTransactionObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Vapor\Contracts\SignedStorageUrlController as SignedStorageUrlContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            SignedStorageUrlContract::class,
            SignedStorageUrlController::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        app()->singleton('settings', function ($app) {
            return new Settings;
        });

        Carbon::macro('timeFormat', function () {
            return $this->format('h:i A');
        });

        Carbon::macro('dateFormat', function () {
            return $this->format('d-m-Y');
        });

        Carbon::macro('dateTimeFormat', function () {
            return $this->format('d-m-Y h:i A');
        });

        Member::observe(MemberObserver::class);
        WalletTransaction::observe(WalletTransactionObserver::class);
        Export::observe(ExportObserver::class);
    }
}
