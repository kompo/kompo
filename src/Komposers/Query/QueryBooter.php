<?php

namespace Kompo\Komposers\Query;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Core\ValidationManager;
use Kompo\Komposers\KomposerManager;
use Kompo\Query;
use Kompo\Routing\RouteFinder;

class QueryBooter
{
    public static function bootForAction($bootInfo)
    {
        $queryClass = $bootInfo['kompoClass'];

        $query = new $queryClass($bootInfo['store']);

        $query->parameter($bootInfo['parameters']);

        KompoId::setForKomposer($query, $bootInfo);

        $query->currentPage(request()->header('X-Kompo-Page'));

        AuthorizationGuard::checkBoot($query, 'Action');

        QueryDisplayer::prepareQuery($query); //setting-up model (like in forms) mainly for 'delete-item' action.

        ValidationManager::addRulesToKomposer($query->rules(), $query);

        QueryFilters::prepareFiltersForAction($query);

        return static::cleanUp($query);
    }

    public static function bootForDisplay($queryClass, array $store = [], $routeParams = null)
    {
        $query = $queryClass instanceof Query ? $queryClass : new $queryClass($store);

        $query->parameter($routeParams ?: RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($query, 'Display');

        ValidationManager::addRulesToKomposer($query->rules(), $query); //for Front-end validations TODO:

        QueryDisplayer::displayFiltersAndCards($query);

        KompoId::setForKomposer($query);

        KompoInfo::saveKomposer($query);

        KomposerManager::booted($query);

        return static::cleanUp($query);
    }

    protected static function cleanUp($query)
    {
        //not needed after filters display, can cause errors if model has an appends attribute
        unset($query->model);

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
}
