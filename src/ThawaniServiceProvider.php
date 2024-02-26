<?php

namespace Ialtoobi\Thawani;

use Ialtoobi\Thawani\Console\Commands\ThawaniCommand;
use Ialtoobi\Thawani\ThawaniManager\ThawaniManager;
use Illuminate\Support\ServiceProvider;

class ThawaniServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ThawaniCommand::class,
            ]);
        }

        //php artisan vendor:publish --tag=config
        $this->publishes([
            __DIR__.'/../config/thawani.php' => config_path('thawani.php'),
        ], 'config');

        // Merge config
        $this->mergeConfigFrom(
            __DIR__. '/../config/thawani.php', 'thawani'
        );
    }

    public function register()
    {

    }

}
