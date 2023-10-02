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
            $this->appendCustomRoutesToWeb();


        }
    }

    // In your package's service provider
    public function appendCustomRoutesToWeb()
    {
        $customRoutes = file_get_contents(__DIR__ . '/../routes/routes.php');

        // Get the path to the web.php file
        $webFilePath = base_path('routes/web.php');

        // Append the custom routes to the web.php file
        file_put_contents($webFilePath, $customRoutes, FILE_APPEND);
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
