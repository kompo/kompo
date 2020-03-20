<?php

namespace Kompo\Routing\Traits;

trait RouteMaking
{
    public static function registerRoute($router, $uri, $objClass)
    {
        return ($layout = static::getMergedLayout($router)) ? 
            static::getViewInLayout($router, $uri, $objClass, $layout) :
            static::getAsJson($router, $uri, $objClass);
    }

    /*********************** PRIVATE ***********************/

    private static function getAsJson($router, $uri, $objClass)
    {
        return $router->get($uri, function() use($objClass) {
            return static::dispatchBooter($objClass);
        });
    }

    private static function getViewInLayout($router, $uri, $objClass, $layout)
    {
        //still need to add this
        //$route->action['extends'] = $extends; //for smart turbolinks

        return $router->get($uri, function () use ($layout, $router, $objClass) {
            return view('kompo::view', [
                'object' => static::dispatchBooter($objClass),
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