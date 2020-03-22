<?php

namespace Kompo\Routing;

use Kompo\Routing\Dispatcher;
use Kompo\Komposers\KomposerManager;

class Router
{
    public static function registerRoute($router, $uri, $komposerClass)
    {
        return ($layout = static::getMergedLayout($router)) ? 
            static::getViewInLayout($router, $uri, $komposerClass, $layout) :
            static::getAsJson($router, $uri, $komposerClass);
    }

    /*********************** PRIVATE ***********************/

    private static function getAsJson($router, $uri, $komposerClass)
    {
        return $router->get($uri, function() use($komposerClass) {
            return with(new Dispatcher($komposerClass))->bootFromRoute();
        });
    }

    private static function getViewInLayout($router, $uri, $komposerClass, $layout)
    {
        //still need to add this
        //$route->action['extends'] = $extends; //for smart turbolinks

        return $router->get($uri, function () use ($layout, $router, $komposerClass) {

            $dispatcher = new Dispatcher($komposerClass);
            $komposer = $dispatcher->bootFromRoute();
            $booter = $dispatcher->booter;

            return view('kompo::view', [
                'vueComponent' => $booter::renderVueComponent($komposer),
                'metaTags' => $komposer->getMetaTags($komposer),
                'layout' => $layout,
                'section' => static::getLastSection($router)
            ]);
        });
    }

    private static function getMergedLayout($router)
    {
        $groupStack = $router->getGroupStack();
        $lastLayouts = end($groupStack)['layout'] ?? false;
        
        return is_array($lastLayouts) ? implode('.', $lastLayouts) : $lastLayouts; //concatenate layout with '.'

    }

    private static function getLastSection($router)
    {
        $sections = $router->current()->getAction('section') ?? 'content';
        
        return is_array($sections) ? end($sections) : $sections; //get last section only
    }
}