<?php

namespace Kompo;

use Illuminate\Support\ServiceProvider;
use Kompo\Routing\Mixins\ExtendsRoutingTrait;

class KompoServiceProvider extends ServiceProvider
{
    use ExtendsRoutingTrait;
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/kompo.php', 'kompo');

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'kompo');

        $this->extendRouting();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
