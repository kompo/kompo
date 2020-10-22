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
        $menu = static::instantiateUnbooted($bootInfo['kompoClass']);

        KompoId::setForKomposer($menu, $bootInfo);

        $menu->store($bootInfo['store']);
        $menu->parameter($bootInfo['parameters']); //Parameters necessary for menus??

        AuthorizationGuard::checkBoot($menu);

        return $menu;
    }

	public static function bootForDisplay($menu, $store = [], $routeParams = null)
	{
        $menu = static::instantiateUnbooted($menu);
        $menu->store($store);
        $menu->parameter($routeParams ?: RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($menu);

		$menu->komponents = collect($menu->komponents())->filter()->all();

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

	/**
     * Returns an unbooted Menu if called with it's class string.
     *
     * @param mixed $class  The class or object
     *
     * @return 
     */
	protected static function instantiateUnbooted($class)
	{
		return $class instanceOf Menu ? $class : new $class(null, true);
	}

}