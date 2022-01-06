<?php

namespace Kompo\Komponents\Menu;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Komponents\KomponentManager;
use Kompo\Menu;
use Kompo\Routing\RouteFinder;

class MenuBooter
{
    public static function bootForAction($bootInfo)
    {
        $menuClass = $bootInfo['kompoClass'];

        $menu = new $menuClass($bootInfo['store']);

        $menu->parameter($bootInfo['parameters']); //Parameters necessary for menus??

        KompoId::setForKomponent($menu, $bootInfo);

        AuthorizationGuard::checkBoot($menu, 'Action');

        return $menu;
    }

    public static function bootForDisplay($menuClass, array $store = [], $routeParams = null)
    {
        $menu = $menuClass instanceof Menu ? $menuClass : new $menuClass($store);

        $menu->parameter($routeParams ?: RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($menu, 'Display');

        $menu->elements = KomponentManager::prepareElementsForDisplay($menu, 'render', true);

        KompoId::setForKomponent($menu);

        KompoInfo::saveKomponent($menu);

        KomponentManager::booted($menu);

        return $menu;
    }
}