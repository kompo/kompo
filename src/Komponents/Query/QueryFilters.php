<?php

namespace Kompo\Komponents\Query;

use Kompo\Core\KompoInfo;
use Kompo\Core\RequestData;
use Kompo\Elements\Managers\FormField;
use Kompo\Komponents\KomponentManager;

class QueryFilters
{
    /**
     * The available slots for placing filters in a query.
     *
     * @var array
     */
    protected static $filtersPlacement = ['top', 'left', 'bottom', 'right'];

    /**
     * Prepare the filters fully for display.
     *
     * @return void
     */
    public static function prepareFiltersForDisplay($query, $suffix = '')
    {
        foreach (static::$filtersPlacement as $placement) {
            $methodName = $placement.$suffix;

            if (method_exists($query, $methodName)) {
                $query->filters[$placement] = KomponentManager::prepareElementsForDisplay($query, $placement.$suffix, true); //fields are also pushed to query
            }
        }
    }

    /**
     * Prepare the filters simply for an action.
     *
     * @return void
     */
    public static function prepareFiltersForAction($query)
    {
        foreach (static::$filtersPlacement as $placement) {
            KomponentManager::prepareElementsForAction($query, $placement, true);
        }

        //don't include Filtering here since some Actions don't require filtering
    }

    /**
     * Handles the query browsing (filtering and sorting).
     *
     * @return self
     */
    public static function filterAndSort($query)
    {
        KomponentManager::collectFields($query)->each(function ($field) use ($query) {
            if (
                (RequestData::get($field->name) || $field->value) //$field->value if default value set
                && !FormField::getConfig($field, 'ignoresModel')
            ) {
                $query->query->handleFilter($field);
            }
        });

        //When sorting and multiple queries are booted, we want to apply the sort on the Komponent from the request only
        if (($sort = request()->header('X-Kompo-Sort')) && KompoInfo::isKomponent($query)) {
            $query->query->handleSort($sort);
        }

        //dd($query->query->getQuery()->toSql(), $query->query->getQuery()->getBindings());
    }
}
