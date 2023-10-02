<?php

namespace Codeintel\TenantFrontendBoilerplate;

use Illuminate\Support\ServiceProvider;

class TenantFrontendBoilerplateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
//        dd('hello from package');
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tenant-frontend-boilerplate');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'tenant-frontend-boilerplate');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('tenant-frontend-boilerplate.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../Models' => app_path('Models'),
            ], 'models');

            $this->publishes([
                __DIR__ . '/../Http/Controllers' => app_path('Http/Controllers'),
            ], 'controllers');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/tenant-frontend-boilerplate'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/tenant-frontend-boilerplate'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'tenant-frontend-boilerplate');

        // Register the main class to use with the facade
        $this->app->singleton('tenant-frontend-boilerplate', function () {
            return new TenantFrontendBoilerplate;
        });
    }
}
