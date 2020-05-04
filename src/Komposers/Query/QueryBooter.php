<?php

namespace Kompo\Komposers\Query;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Core\ValidationManager;
use Kompo\Komposers\Query\QueryFilters;
use Kompo\Query;
use Kompo\Routing\RouteFinder;

class QueryBooter
{
    public static function bootForAction($bootInfo)
    {
        $query = static::instantiateUnbooted($bootInfo['kompoClass']);

        KompoId::setForKomposer($query, $bootInfo);

        $query->store($bootInfo['store']);
        $query->parameter($bootInfo['parameters']);
        
        $query->currentPage(request()->header('X-Kompo-Page'));
        
        AuthorizationGuard::checkBoot($query);

        QueryDisplayer::prepareQuery($query); //setting-up model (like in forms) mainly for 'delete-item' action. 

        ValidationManager::addRulesToKomposer($query->rules(), $query); 
        
        QueryFilters::prepareFiltersForAction($query);

        return $query;
    }

	public static function bootForDisplay($query, $store = [])
	{
        $query = static::instantiateUnbooted($query);
        $query->store($store);
        $query->parameter(RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($query);

        ValidationManager::addRulesToKomposer($query->rules(), $query); //for Front-end validations TODO:

		QueryDisplayer::displayFiltersAndCards($query);

        KompoInfo::saveKomposer($query); 

        return $query;
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