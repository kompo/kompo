<?php

namespace Kompo;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Kompo\Http\KompoResponse;
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
        
        // Register response macros
        $this->registerResponseMacros();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MakeForm::class,
                Commands\MakeQuery::class,
                Commands\MakeTable::class,
                Commands\MakeMenu::class,
                Commands\PullCode::class,
                Commands\CreateCacheFolders::class,
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

        $packageHelpers = collect([
            __DIR__.'/../Core/KompoHelpers.php', 
            __DIR__.'/../Core/HelperUtils.php',
        ])->concat(
            collect(\File::allFiles(__DIR__.'/../Helpers'))->map(fn($file) => $file->getRealPath())
        );

        $autoloadedHelpers->concat($packageHelpers)->each(function ($path) {
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }

    /**
     * Register response macros
     */
    protected function registerResponseMacros()
    {
        ResponseFactory::macro('modal', function ($content, $options = []) {
            return KompoResponse::modal($content, $options);
        });

        ResponseFactory::macro('panel', function ($content, $panelId, $options = []) {
            return KompoResponse::panel($content, $panelId, $options);
        });

        ResponseFactory::macro('drawer', function ($content, $options = []) {
            return KompoResponse::drawer($content, $options);
        });

        ResponseFactory::macro('popup', function ($content, $options = []) {
            return KompoResponse::popup($content, $options);
        });

        ResponseFactory::macro('kompoRedirect', function ($url, $options = []) {
            return KompoResponse::redirect($url, $options);
        });

        ResponseFactory::macro('kompoAlert', function ($message, $type = 'success', $options = []) {
            return KompoResponse::alert($message, $type, $options);
        });

        ResponseFactory::macro('kompoRefresh', function ($kompoids = null, $data = null) {
            return KompoResponse::refresh($kompoids, $data);
        });

        ResponseFactory::macro('kompoUpdateElements', function (array $elements, ?string $kompoid = null, ?string $transition = null) {
            return KompoResponse::updateElements($elements, $kompoid, $transition);
        });

        ResponseFactory::macro('closeModal', function () {
            return KompoResponse::run('({modal}) => modal().close()');
        });

        ResponseFactory::macro('kompoRun', function (string $jsFunction, $data = null) {
            return KompoResponse::run($jsFunction, $data);
        });

        ResponseFactory::macro('kompoUpdateElementValues', function (array $updates) {
            return KompoResponse::updateElementValues($updates);
        });

        ResponseFactory::macro('kompoUpdateLabel', function (string $elementId, string $label) {
            return KompoResponse::updateLabel($elementId, $label);
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
