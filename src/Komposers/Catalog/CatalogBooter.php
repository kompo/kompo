<?php

namespace Kompo\Komposers\Catalog;

use Kompo\Catalog;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\SessionStore;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\RouteFinder;

class CatalogBooter extends Catalog
{
	public function __construct()
	{
        //overriden
	}

	public static function bootForDisplay($catalog, $store = [])
	{
        $catalog = static::instantiateUnbooted($catalog);
        $catalog->store($store);
        $catalog->parameter(RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($catalog);

        KomposerManager::created($catalog);

		//continue boot

		SessionStore::saveKomposer($catalog);

		return $catalog;
	}

    /**
     * Shortcut method to render a Catalog into it's Vue component.
     *
     * @return string
     */
    public static function renderVueComponent($catalog)
    {
        return '<vl-catalog :vcomponent="'.htmlspecialchars($catalog).'"></vl-catalog>';
    }

	/**
     * Returns an unbooted Catalog if called with it's class string.
     *
     * @param mixed $class  The class or object
     *
     * @return 
     */
	protected static function instantiateUnbooted($class)
	{
		return $class instanceOf Catalog ? $class : new $class(null, true);
	}

}