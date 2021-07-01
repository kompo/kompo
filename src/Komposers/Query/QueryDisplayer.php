<?php

namespace Kompo\Komposers\Query;

use Illuminate\Database\Eloquent\Builder as LaravelEloquentBuilder;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\Relations\Relation as LaravelRelation;
use Illuminate\Database\Query\Builder as LaravelQueryBuilder;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\CardGenerator;
use Kompo\Core\KompoTarget;
use Kompo\Core\Util;
use Kompo\Core\ValidationManager;
use Kompo\Database\CollectionQuery;
use Kompo\Database\DatabaseQuery;
use Kompo\Database\EloquentQuery;
use Kompo\Exceptions\BadQueryDefinitionException;
use Kompo\Routing\RouteFinder;

class QueryDisplayer
{
    /**
     * For initial display: Prepares the query, the query, filters and cards.
     *
     * @param Kompo\Query $query
     *
     * @return Kompo\Query
     */
    public static function displayFiltersAndCards($query)
    {
        static::prepareConfigurations($query);

        static::prepareQuery($query);

        QueryFilters::prepareFiltersForDisplay($query);

        QueryFilters::filterAndSort($query);

        CardGenerator::getTransformedCards($query);

        QueryFilters::prepareFiltersForDisplay($query, 'OnLoad');

        return $query;
    }

    /**
     * For browsing: Filters, Sorts, Paginates Cards.
     *
     * @param Kompo\Query $query
     *
     * @return Illuminate\...\Paginator
     */
    public static function browseCards($query)
    {
        AuthorizationGuard::mainGate($query, 'browsing');

        ValidationManager::validateRequest($query);

        QueryFilters::filterAndSort($query);

        CardGenerator::getTransformedCards($query);

        return $query->query;
    }

    /**
     * Initialize the browsing, ordering and optionnally set the headers of a table query.
     *
     * @param Kompo\Query $query
     *
     * @return void
     */
    protected static function prepareConfigurations($komposer)
    {
        RouteFinder::activateRoute($komposer);

        $komposer->noItemsFound = method_exists($komposer, 'noItemsFound') ?
            $komposer->noItemsFound() : __($komposer->noItemsFound);

        if (method_exists($komposer, 'headers')) {
            $komposer->headers = collect($komposer->headers())->filter();
        }

        if (method_exists($komposer, 'footers')) {
            $komposer->footers = collect($komposer->footers())->filter();
        }

        if ($komposer->layout == 'Kanban') {
            KompoTarget::setOnElement($komposer, KompoTarget::getEncrypted('changeStatus'));
        }
    }

    /**
     * Prepare the underlying query that will fetch the komposer cards.
     *
     * @param Kompo\Query $komposer
     *
     * @return void
     */
    public static function prepareQuery($komposer)
    {
        if (CardGenerator::isSpecialQueryLayout($komposer)) {
            $komposer->perPage = 1000000; //Laravel limitation, besides nobody will ever visualize 1M items...
            $komposer->passLocale();
        } 

        $q = $komposer->query();

        if ($q instanceof LaravelModel || $q instanceof LaravelEloquentBuilder || $q instanceof LaravelRelation) {
            $komposer->model = $q->getModel();

            $komposer->keyName = $komposer->model->getKeyName();

            $komposer->query = new EloquentQuery($q, $komposer);
        } elseif ($q instanceof LaravelQueryBuilder) {
            $komposer->keyName = $komposer->keyName; //TODO: try to guess table primarykey

            $komposer->query = new DatabaseQuery($q, $komposer);
        } elseif (Util::isCollection($q) || is_array($q) || $q === null) {
            $komposer->query = new CollectionQuery($q, $komposer);
        } else {
            throw new BadQueryDefinitionException(class_basename($komposer));
        }
    }
}
