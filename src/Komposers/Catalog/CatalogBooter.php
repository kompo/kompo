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

    public static function performAction($session)
    {
        $catalog = static::instantiateUnbooted($session['kompoClass']);

        $catalog->store($session['store']);
        $catalog->parameter($session['parameters']);

        switch(request()->header('X-Kompo-Action'))
        {
            case 'browse':
                return CatalogDisplayer::browseCards($catalog);

            case 'order':
                return FormSubmitter::callCustomHandle($catalog);

            case 'delete':
                return FormManager::getMatchedSelectOptions($catalog);
        }

    }

	public static function bootForDisplay($catalog, $store = [])
	{
        $catalog = static::instantiateUnbooted($catalog);
        $catalog->store($store);
        $catalog->parameter(RouteFinder::getRouteParameters());

		return CatalogDisplayer::displayFiltersAndCards($catalog);
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