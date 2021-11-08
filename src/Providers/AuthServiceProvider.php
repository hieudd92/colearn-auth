<?php

namespace CoLearn\Auth\Providers;

use CoLearn\Auth\RpcGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package CoLearn\Auth
 */
class AuthServiceProvider extends ServiceProvider
{
	/**
     * Register the Config provider
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        $this->publishes([
            __DIR__ . '/../../config/colearn_auth.php' => config_path('colearn_auth.php'),
        ]);

        Auth::extend('rpc', function ($app, $name, array $config) {
            return new RpcGuard($app['request'], $app['config']);
        });
    }

	/**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // code
    }
}