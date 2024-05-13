<?php

namespace BardanIO\SimpleId;

use Illuminate\Support\ServiceProvider;

class SimpleIdServiceProvider extends ServiceProvider
{
    /**
     * Boot method of this service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/simple-id.php' => config_path('simple-id.php'),
        ]);

        $this->app->make(SimpleIdRegistrar::class)->registerModels(config('simple-id.models'));
    }

    /**
     * Register method of this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/simple-id.php',
            'simple-id'
        );

        $this->app->bind(SimpleIdRegistrar::class, function () {
            return new SimpleIdRegistrar();
        });
    }
}