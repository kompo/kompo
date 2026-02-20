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
                $query->filters[$placement] = $query->prepareOwnElementsForDisplay($query->{$methodName}()); //fields are also pushed to query
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
        $query->prepareOwnElementsForAction($query->top()); //explicitely writing method names for IDE support
        $query->prepareOwnElementsForAction($query->left());
        $query->prepareOwnElementsForAction($query->bottom());
        $query->prepareOwnElementsForAction($query->right());

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

        static::applyThFilters($query);

        //When sorting and multiple queries are booted, we want to apply the sort on the Komponent from the request only
        if (($sort = request()->header('X-Kompo-Sort')) && KompoInfo::isKomponent($query)) {
            $query->query->handleSort($sort);
        }

        //dd($query->query->getQuery()->toSql(), $query->query->getQuery()->getBindings());
    }

    /**
     * Apply Th header filters (filterBy and slicer) to the query.
     *
     * @param Kompo\Query $query
     *
     * @return void
     */
    private static function applyThFilters($query)
    {
        if (!method_exists($query, 'headers')) {
            return;
        }

        collect($query->headers())->filter()->each(function ($header) use ($query) {
            if (!($header instanceof \Kompo\Th)) {
                return;
            }

            $filterName = $header->config('filterName');

            if ($filterName) {
                $value = request($filterName);

                if ($value !== null && $value !== '' && !is_array($value)) {
                    $operator = !empty($header->config('filterOptions')) ? '=' : 'LIKE';
                    $query->query->applyThFilter($filterName, $operator, $value);
                }
            }

            $slicerName = $header->config('slicerName');

            if ($slicerName) {
                $value = request($slicerName);

                if ($value && is_array($value)) {
                    $query->query->applyThFilter($slicerName, 'IN', $value);
                }
            }
        });
    }
}
