<?php

namespace Kompo\Komposers\Menu;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Komposers\KomposerManager;
use Kompo\Menu;
use Kompo\Routing\RouteFinder;

class MenuBooter
{
    public static function bootForAction($bootInfo)
    {
        $menuClass = $bootInfo['kompoClass'];

        $menu = new $menuClass($bootInfo['store']);

        $menu->parameter($bootInfo['parameters']); //Parameters necessary for menus??

        KompoId::setForKomposer($menu, $bootInfo);

        AuthorizationGuard::checkBoot($menu, 'Action');

        return $menu;
    }

    public static function bootForDisplay($menuClass, array $store = [], $routeParams = null)
    {
        $menu = $menuClass instanceof Menu ? $menuClass : new $menuClass($store);

        $menu->parameter($routeParams ?: RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($menu, 'Display');

        $menu->komponents = KomposerManager::prepareKomponentsForDisplay($menu, 'komponents', true);

        KompoId::setForKomposer($menu);

        KompoInfo::saveKomposer($menu);

        KomposerManager::booted($menu);

        return $menu;
    }

    /**
     * Shortcut method to render a Menu into it's Vue component.
     *
     * @return string
     */
    public static function renderVueComponent($menu)
    {
        return '<vl-menu :vkompo="'.htmlspecialchars($menu).'"></vl-menu>';
    }
}
