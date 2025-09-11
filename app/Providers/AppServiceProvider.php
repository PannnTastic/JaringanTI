<?php

namespace App\Providers;

use App\Auth\LdapUserProvider;
use App\Services\LdapAuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register LDAP service as singleton
        $this->app->singleton(LdapAuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Document::class, \App\Policies\DocumentPolicy::class);

        // Register LDAP user provider
        Auth::provider('ldap', function ($app, array $config) {
            return new LdapUserProvider(
                $app['hash'],
                $config['model'],
                $app->make(LdapAuthService::class)
            );
        });
    }
}
