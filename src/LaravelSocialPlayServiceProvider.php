<?php

namespace Bulkmake\LaravelSocialPlay;

use Illuminate\Support\ServiceProvider;

class LaravelSocialPlayServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bulkmake');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'bulkmake');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/social-play.php', 'social-play');

        // Register the service the package provides.
        $this->app->singleton('social-play', function ($app) {
            return new LaravelSocialPlay;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['social-play'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/social-play.php' => config_path('social-play.php'),
        ], 'social-play.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/bulkmake'),
        ], 'social-play.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/bulkmake'),
        ], 'social-play.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/bulkmake'),
        ], 'social-play.lang');*/

        // Registering package commands.
        $this->commands([
            Console\Commands\ExampleCommand::class,
            Console\Commands\FacebookCreatePost::class,
            Console\Commands\InstagramCreatePost::class,
            Console\Commands\PinterestCreatePost::class,
            Console\Commands\GoogleCreatePost::class,
            Console\Commands\GoogleCreateMedia::class,
        ]);
    }
}
