<?php

namespace Kompo\Routing;

use Kompo\Catalog;
use Kompo\Core\SessionStore;
use Kompo\Exceptions\NotBootableFromRouteException;
use Kompo\Form;
use Kompo\Komposers\Catalog\CatalogBooter;
use Kompo\Komposers\Form\FormBooter;
use Kompo\Komposers\Menu\MenuBooter;
use Kompo\Menu;
use Kompo\Routing\Traits\RouteFinding;
use Kompo\Routing\Traits\RouteMaking;

class Router
{
    use RouteMaking, RouteFinding;
    
    public static function dispatchConnection()
    {
        $x = SessionStore::getKompo();
        $manager = static::getManagerClass($x['kompoClass']);
        return $manager::performAction($x);        
    }

    public static function dispatchBooter($komposerClass)
    {
        $manager = static::getManagerClass($komposerClass);
        return $manager::bootForDisplay(
            $komposerClass, 
            request('id'), //TODO: change to header(X-Kompo-modelKey) one day
            request('store') //TODO: change to header - think of something);
        );
    }

    public static function getManagerClass($komposerClass)
    {
        if(is_a($komposerClass, Form::class, true)){
            return FormBooter::class;
        }elseif (is_a($komposerClass, Catalog::class, true)) {
            return CatalogBooter::class;
        }elseif (is_a($komposerClass, Menu::class, true)) {
            return MenuBooter::class;
        }
        throw new NotBootableFromRouteException($komposerClass);
    }


    public static function getRouteParameters()
    {
        return request()->route() ? request()->route()->parameters() : [];
        //Other options:
        
        //Option: 1
        //$request->route()->parametersWithoutNulls()
        
        //Option: 2
        //$names = $request->route()->parameterNames();
        //return collect($request->route()->parameters())->filter(function($param, $key) use ($names){
        //    return in_array($key, $names);
        //})->all();
    }
}