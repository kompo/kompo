<?php

namespace Kompo\Komposers\Query;

use Kompo\Query;
use Kompo\Core\AuthorizationGuard;
use Kompo\Routing\RouteFinder;

class QueryBooter
{
    public static function bootForAction($session)
    {
        $query = static::instantiateUnbooted($session['kompoClass']);

        $query->store($session['store']);
        $query->parameter($session['parameters']);
        
        $query->currentPage(request()->header('X-Kompo-Page'));
        
        AuthorizationGuard::checkBoot($query);

        QueryDisplayer::prepareQuery($query); //for setting-up model to be able to delete

        QueryFilters::prepareFiltersForAction($query);

        return $query;
    }

	public static function bootForDisplay($query, $store = [])
	{
        $query = static::instantiateUnbooted($query);
        $query->store($store);
        $query->parameter(RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($query);

		return QueryDisplayer::displayFiltersAndCards($query);
	}

    /**
     * Shortcut method to render a Query into it's Vue component.
     *
     * @return string
     */
    public static function renderVueComponent($query)
    {
        return '<vl-query :vkompo="'.htmlspecialchars($query).'"></vl-query>';
    }

	/**
     * Returns an unbooted Query if called with it's class string.
     *
     * @param mixed $class  The class or object
     *
     * @return 
     */
	protected static function instantiateUnbooted($class)
	{
		return $class instanceOf Query ? $class : new $class(null, true);
	}

}