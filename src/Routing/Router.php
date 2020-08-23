<?php

namespace Kompo\Routing;

use Kompo\Routing\Dispatcher;

class Router
{
    /**
     * Registers a Kompo route that loads a Komposer either in a view or as a JSON object.
     *
     * @param      <type>  $router         The router
     * @param      <type>  $uri            The uri
     * @param      <type>  $komposerClass  The komposer class
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public static function registerRoute($router, $uri, $komposerClass)
    {
        return ($layout = static::getMergedLayout($router)) ? 
            static::getViewInLayout($router, $uri, $komposerClass, $layout) :
            static::getAsJson($router, $uri, $komposerClass);
    }

    /*********************** PRIVATE ***********************/

    /**
     * Gets as json.
     *
     * @param      <type>  $router         The router
     * @param      <type>  $uri            The uri
     * @param      <type>  $komposerClass  The komposer class
     *
     * @return     <type>  As json.
     */
    private static function getAsJson($router, $uri, $komposerClass)
    {
        return $router->get($uri, function() use($komposerClass) {
            return with(new Dispatcher($komposerClass))->bootKomposerForDisplay();
        });
    }

    /**
     * Gets the view in layout.
     *
     * @param      <type>  $router         The router
     * @param      <type>  $uri            The uri
     * @param      <type>  $komposerClass  The komposer class
     * @param      <type>  $layout         The layout
     *
     * @return     <type>  The view in layout.
     */
    private static function getViewInLayout($router, $uri, $komposerClass, $layout)
    {
        //still need to add this
        //$route->action['extends'] = $extends; //for smart turbolinks

        return $router->get($uri, function () use ($layout, $router, $komposerClass) {

            $dispatcher = new Dispatcher($komposerClass);
            $komposer = $dispatcher->bootKomposerForDisplay();
            $booter = $dispatcher->booter;

            return view('kompo::view', [
                'vueComponent' => $booter::renderVueComponent($komposer),
                'containerClass' => property_exists($komposer, 'containerClass') ? $komposer->containerClass : 'container',
                'metaTags' => $komposer->getMetaTags($komposer),
                'layout' => $layout,
                'section' => static::getLastSection($router)
            ]);
        });
    }

    /*********************** PRIVATE ^2 ***********************/

    /**
     * Gets the merged layout.
     *
     * @param      <type>  $router  The router
     *
     * @return     <type>  The merged layout.
     */
    private static function getMergedLayout($router)
    {
        $groupStack = $router->getGroupStack();
        $lastLayouts = end($groupStack)['layout'] ?? false;
        
        return is_array($lastLayouts) ? implode('.', $lastLayouts) : $lastLayouts; //concatenate layout with '.'

    }

    /**
     * Gets the last section.
     *
     * @param      <type>  $router  The router
     *
     * @return     <type>  The last section.
     */
    private static function getLastSection($router)
    {
        $sections = $router->current()->getAction('section') ?? 'content';
        
        return is_array($sections) ? end($sections) : $sections; //get last section only
    }
}