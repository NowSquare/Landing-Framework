<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('owner-management', function ($user) {
            return $user->hasRole(['owner']);
        });

        Gate::define('reseller-management', function ($user) {
            return $user->hasRole(['owner']) || $user->hasRole(['reseller']);
        });

        Gate::define('admin-management', function ($user) {
            return $user->hasRole(['owner']) || $user->hasRole(['reseller']) || $user->hasRole(['admin']);
        });

        Gate::define('limitation', function ($user, $limitation) {
          if ($limitation != null) {
            if ($user->plan == null) {
              // Free plan
              if ($limitation != 'account.plan_visible') return false;
            } else {
              $value = array_get($user->plan->limitations, $limitation);

              if ($value == 0) {
                return false;
              }
            }
          }

          return true;
        });
    }
}
