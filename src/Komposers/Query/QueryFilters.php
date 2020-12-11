<?php

namespace Kompo\Komposers\Query;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoInfo;
use Kompo\Core\RequestData;
use Kompo\Komponents\Managers\FormField;
use Kompo\Komposers\Form\FormDisplayer;
use Kompo\Komposers\Form\FormSubmitter;
use Kompo\Komposers\KomposerManager;

class QueryFilters
{
    /**
     * The available slots for placing filters in a query.
     *
     * @var        array
     */
    protected static $filtersPlacement = [ 'top', 'left', 'bottom', 'right' ];

    /**
     * Prepare the filters fully for display.
     *
     * @return void
     */
    public static function prepareFiltersForDisplay($query)
    {
        foreach (static::$filtersPlacement as $placement) {

            $query->filters[$placement] = KomposerManager::prepareKomponentsForDisplay($query, $placement, true); //fields are also pushed to query

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

            KomposerManager::prepareKomponentsForAction($query, $placement, true);

        }

        //don't include Filtering here since some Actions don't require filtering
    }

    /**
     * Handles the query browsing (filtering and sorting)
     * 
     * @return self
     */
    public static function filterAndSort($query)
    {
        KomposerManager::collectFields($query)->each(function($field) use($query) {

            if(
                (RequestData::get($field->name) || $field->value) //$field->value if default value set
                && !FormField::getConfig($field, 'ignoresModel')
            )
                $query->query->handleFilter($field);

        });

        //When sorting and multiple queries are booted, we want to apply the sort on the Komposer from the request only
        if(($sort = request()->header('X-Kompo-Sort')) && KompoInfo::isKomposer($query))
            $query->query->handleSort($sort);

        //dd($query->query->getQuery()->toSql(), $query->query->getQuery()->getBindings());
    }
}