<?php

namespace ezavalishin\VKMA;

use ezavalishin\Guards\VKMAGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class VKMAServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ezavalishin');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'ezavalishin');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Auth::viaRequest('vkma', function (Request $request) {
            return (new VKMAGuard(
                $request,
                config('vkma.app_secret'),
                config('vkma.options.model'),
                config('vkma.options.vk_id_key')
            ))->user();
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/vkma.php', 'vkma');

        // Register the service the package provides.
        $this->app->singleton('vkma', static function ($app) {
            return new VKMA(
                config('vkma.app_id'),
                config('vkma.service_key'),
                config('app.locale')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['vkma'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/vkma.php' => config_path('vkma.php'),
        ], 'vkma.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/ezavalishin'),
        ], 'vkma.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/ezavalishin'),
        ], 'vkma.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/ezavalishin'),
        ], 'vkma.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
