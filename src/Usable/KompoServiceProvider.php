<?php

namespace Kompo;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Kompo\Http\Middleware\SetKompoLocaleMiddleware;
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

        $this->loadJSONTranslationsFrom(__DIR__.'/../../resources/lang');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'kompo');

        $this->extendRouting();

        static::registerHelpers();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MakeForm::class,
                Commands\MakeQuery::class,
                Commands\MakeTable::class,
                Commands\MakeMenu::class,
            ]);
        }

        if (count(config('kompo.locales'))) {
            $kernel = $this->app->make(Kernel::class);

            $kernel->appendMiddlewareToGroup('web', SetKompoLocaleMiddleware::class);
            //app('router')->pushMiddlewareToGroup('web', SetKompoLocaleMiddleware::class);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        static::initializeBootFlag();
    }

    public static function initializeBootFlag()
    {
        //When active, komposers are booted on instantiation.
        app()->instance('bootFlag', false);        
    }

    public static function registerHelpers()
    {
        collect([
            '/../Core/KompoHelpers.php',
            '/../Core/HelperUtils.php',
        ])->each(function ($path) {
            if (file_exists($file = __DIR__.$path)) {
                require_once $file;
            }
        });
    }
}
