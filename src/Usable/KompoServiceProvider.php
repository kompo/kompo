<?php

namespace Kompo;

use Illuminate\Support\ServiceProvider;
use Kompo\Http\Middleware\SetKompoLocaleMiddleware;
use Kompo\Routing\Mixins\ExtendsRoutingTrait;
use Illuminate\Contracts\Http\Kernel;

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

        if (file_exists($file = __DIR__.'/../Core/KompoHelpers.php'))
            require_once $file;


        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MakeForm::class,
                Commands\MakeQuery::class,
                Commands\MakeTable::class,
                Commands\MakeMenu::class,
            ]);
        }

        if(count(config('kompo.locales'))){
            $kernel = $this->app->make(Kernel::class);

            $kernel->appendMiddlewareToGroup('web', SetKompoLocaleMiddleware::class);
            //app('router')->pushMiddlewareToGroup('web', SetKompoLocaleMiddleware::class);
        }

        /** @var Router $router */
        /*$router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', SetKompoLocaleMiddleware::class);*/
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
