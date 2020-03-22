<?php

namespace Kompo\Komposers\Menu;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\SessionStore;
use Kompo\Komposers\KomposerManager;
use Kompo\Menu;
use Kompo\Routing\RouteFinder;

class MenuBooter extends Menu
{
	public function __construct()
	{
        //overriden
	}

	public static function bootForDisplay($menu, $store = [])
	{
        $menu = static::instantiateUnbooted($menu);
        $menu->store($store);
        $menu->parameter(RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($menu);

        KomposerManager::created($menu);

		$menu->components = collect($menu->components())->filter()->all();

		SessionStore::saveKomposer($menu);

		return $menu;
	}

    /**
     * Shortcut method to render a Menu into it's Vue component.
     *
     * @return string
     */
    public static function renderVueComponent($menu)
    {
        return '<vl-menu :vcomponent="'.htmlspecialchars($menu).'"></vl-menu>';
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