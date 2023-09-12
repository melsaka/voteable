<?php

namespace Melsaka\Voteable;

use Illuminate\Support\ServiceProvider;

class VoteableServiceProvider extends ServiceProvider
{
    // package migrations
    private $migration = __DIR__ . '/database/migrations/';

    private $config = __DIR__ . '/config/voteable.php';


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->config, 'voteable');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([ $this->migration ]);

        $this->publishes([ $this->config => config_path('voteable.php') ], 'voteable');
    }
}
