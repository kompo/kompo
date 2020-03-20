<?php

namespace Kompo\Komposers\Catalog;

use Kompo\Catalog;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\SessionStore;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\Router;

class CatalogBooter extends Catalog
{
	public function __construct()
	{
	}

	protected static function instantiateUnbooted($class)
	{
		return $class instanceOf Catalog ? $class : new $class(null, true);
	}

	public static function bootForDisplay($catalog, $store = [])
	{
        $catalog = static::instantiateUnbooted($catalog);
        $catalog->store($store);
        $catalog->parameter(Router::getRouteParameters());

        AuthorizationGuard::checkBoot($catalog);

        KomposerManager::created($catalog);

		//continue boot

		SessionStore::saveKomposer($catalog);

		return $catalog;
	}

}