<?php

namespace App\Providers;

use App\Services\ProxmoxAuthService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class ProxmoxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        info('New Proxmox Service Instance created by ');
        
        $this->app->singleton(ProxmoxAuthService::class, function($app) {
            return new ProxmoxAuthService(
                'root',
                'Nortel01',
                'pam',
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
