<?php

namespace Kompo\Komposers\Menu;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\SessionStore;
use Kompo\Komposers\KomposerManager;
use Kompo\Menu;
use Kompo\Routing\Router;

class MenuBooter extends Menu
{
	public function __construct()
	{
	}

	protected static function instantiateUnbooted($class)
	{
		return $class instanceOf Menu ? $class : new $class(null, true);
	}

	public static function bootForDisplay($menu, $store = [])
	{
        $menu = static::instantiateUnbooted($menu);
        $menu->store($store);
        $menu->parameter(Router::getRouteParameters());

        AuthorizationGuard::checkBoot($menu);

        KomposerManager::created($menu);

		//continue boot

		SessionStore::saveKomposer($menu);

		return $menu;
	}

}