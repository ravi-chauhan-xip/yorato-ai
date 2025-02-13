<?php

namespace App\Providers;

use App\Models\Admin;
use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user instanceof Admin && $user->is_super ? true : null;
        });

        Gate::define('viewWebTinker', function ($user = null) {
            return Auth::guard('admin')->user()?->is_super;
        });
    }
}
