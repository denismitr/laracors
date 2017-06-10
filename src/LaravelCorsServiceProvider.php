<?php

namespace Denismitr\Laracors;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class LaravelCorsServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/config/laracors.php' => config_path('laracors.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/laracors.php', 'laracors'
        );
    }
}