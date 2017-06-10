<?php

namespace Denismitr\Laracors;

use Illuminate\Support\ServiceProvider;

class LumenServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->app->configure(Cors::CONFIG_KEY);
    }
}