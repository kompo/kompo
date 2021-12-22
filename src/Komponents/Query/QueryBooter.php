<?php

namespace Kompo\Komponents\Query;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Core\ValidationManager;
use Kompo\Komponents\KomponentManager;
use Kompo\Query;
use Kompo\Routing\RouteFinder;

class QueryBooter
{
    public static function bootForAction($bootInfo)
    {
        $queryClass = $bootInfo['kompoClass'];

        $query = new $queryClass($bootInfo['store']);

        $query->parameter($bootInfo['parameters']);

        $initialModel = $query->model;

        KompoId::setForKomponent($query, $bootInfo);

        $query->currentPage(request()->header('X-Kompo-Page'));

        AuthorizationGuard::checkBoot($query, 'Action');

        QueryDisplayer::prepareQuery($query); //setting-up model (like in forms) mainly for 'delete-item' action.

        ValidationManager::addRulesToKomponent($query->rules(), $query);

        QueryFilters::prepareFiltersForAction($query);

        return static::cleanUp($query, $initialModel);
    }

    public static function bootForDisplay($queryClass, array $store = [], $routeParams = null)
    {
        $query = $queryClass instanceof Query ? $queryClass : new $queryClass($store);

        $query->parameter($routeParams ?: RouteFinder::getRouteParameters());

        $initialModel = $query->model;

        AuthorizationGuard::checkBoot($query, 'Display');

        ValidationManager::addRulesToKomponent($query->rules(), $query); //for Front-end validations TODO:

        QueryDisplayer::displayFiltersAndCards($query);

        KompoId::setForKomponent($query);

        KompoInfo::saveKomponent($query);

        KomponentManager::booted($query);

        return static::cleanUp($query, $initialModel);
    }

    protected static function cleanUp($query, $initialModel)
    {
        //reset after filters display, can cause errors if model has an appends attribute
        $query->model = $initialModel;

        return $query;
    }
}
