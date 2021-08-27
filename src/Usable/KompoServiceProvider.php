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
                Commands\PullCode::class,
            ]);
        }

        if (count(config('kompo.locales'))) {
            $kernel = $this->app->make(Kernel::class);

            $kernel->appendMiddlewareToGroup('web', SetKompoLocaleMiddleware::class);
            //app('router')->pushMiddlewareToGroup('web', SetKompoLocaleMiddleware::class);
        }

         //Usage: php artisan vendor:publish --provider="Kompo\KompoServiceProvider"
        $this->publishes([
            __DIR__.'/../../config/kompo.php' => config_path('kompo.php'),
        ]);
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
        $autoloadHelpers = collect(
            \File::allFiles(base_path(config('kompo.helpers_dir'))) //All files in this directory will be loaded as helpers
        )->map(fn($file) => $file->getRealPath());

        $packageHelpers = [
            __DIR__.'/../Core/KompoHelpers.php', 
            __DIR__.'/../Core/HelperUtils.php',
        ];

        $autoloadHelpers->concat($packageHelpers)->each(function ($path) {
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }
}
