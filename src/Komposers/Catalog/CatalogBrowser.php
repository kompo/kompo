<?php

namespace Kompo\Komposers\Catalog;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\CardPaginator;
use Kompo\Core\RequestData;
use Kompo\Komposers\Form\FormDisplayer;
use Kompo\Komposers\Form\FormSubmitter;
use Kompo\Komposers\KomposerManager;

class CatalogBrowser extends CatalogBooter
{
    /**
     * The available slots for placing filters in a catalog.
     *
     * @var        array
     */
    protected static $filtersPlacement = [ 'top', 'left', 'bottom', 'right' ];

    public static function browseCards($catalog)
    {
        CatalogDisplayer::prepareQuery($catalog);

        static::prepareFiltersForAction($catalog);

        CardPaginator::transformItems($catalog);

        return $catalog->query;
    }

    /**
     * Prepare the filters fully for display.
     *
     * @return void
     */
    protected static function prepareFiltersForDisplay($komposer)
    {
        foreach (static::$filtersPlacement as $placement) {

            $komposer->filters[$placement] = KomposerManager::prepareComponentsForDisplay($komposer, $placement); //fields are also pushed to catalog

        }

        static::filterAndSort($komposer);
    }

    /**
     * Prepare the filters simply for an action.
     *
     * @return void
     */
    protected static function prepareFiltersForAction($komposer)
    {
        foreach (static::$filtersPlacement as $placement) {

            KomposerManager::prepareComponentsForAction($komposer, $placement);

        }

        static::filterAndSort($komposer);
    }

    /**
     * Handles the catalog browsing (filtering and sorting)
     * 
     * @return self
     */
    public static function filterAndSort($komposer)
    {
        KomposerManager::collectFields($komposer)->each(function($field) use($komposer) {

            if(RequestData::fieldShouldFilter($field) || $field->value){
                
                $komposer->query->handleFilter($field);

            }
        });

        if($sort = request()->header('X-Kompo-Sort'))
            $komposer->query->handleSort($sort);

    }
}