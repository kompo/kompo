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

        static::initializeCurrentKomponent();
    }

    public static function initializeBootFlag()
    {
        //When active, komponents are booted on instantiation.
        app()->instance('bootFlag', false);        
    }

    public static function initializeCurrentKomponent()
    {
        app()->instance('currentKomponent', null);
    }

    public static function registerHelpers()
    {
        $helpersDir = base_path(config('kompo.helpers_dir')); //All files in this directory will be loaded as helpers

        $autoloadedHelpers = collect(is_dir($helpersDir) ? \File::allFiles($helpersDir) : [])
            ->map(fn($file) => $file->getRealPath());

        $packageHelpers = [
            __DIR__.'/../Core/KompoHelpers.php', 
            __DIR__.'/../Core/HelperUtils.php',
        ];

        $autoloadedHelpers->concat($packageHelpers)->each(function ($path) {
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }

    protected function registerBladeDirectives()
    {
        \Blade::directive('kompoStyles', function ($style = null) {

            $styleFilename = 'app'.($style ? '-'.$style : '');

            return '<link href="https://unpkg.com/vue-kompo@^3/dist/app'.$styleFilename.'.min.css" rel="stylesheet">';

        });

        \Blade::directive('kompoScripts', function () {

            return '<script src="https://unpkg.com/vue-kompo@^3/dist/app.min.js"></script>';
            
        });
    }
}
