<?php

namespace Kompo\Komponents\Query;

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
    protected static function prepareConfigurations($komponent)
    {
        RouteFinder::activateRoute($komponent);

        $komponent->noItemsFound = method_exists($komponent, 'noItemsFound') ?
            $komponent->noItemsFound() : __($komponent->noItemsFound);

        if (method_exists($komponent, 'headers')) {
            $komponent->headers = collect($komponent->headers())->filter();
        }

        if (method_exists($komponent, 'footers')) {
            $komponent->footers = collect($komponent->footers())->filter();
        }

        if ($komponent->layout == 'Kanban') {
            KompoTarget::setForBaseElement($komponent, KompoTarget::getEncrypted('changeStatus'));
        }
    }

    /**
     * Prepare the underlying query that will fetch the komponent cards.
     *
     * @param Kompo\Query $komponent
     *
     * @return void
     */
    public static function prepareQuery($komponent)
    {
        if (CardGenerator::isSpecialQueryLayout($komponent)) {
            $komponent->perPage = 1000000; //Laravel limitation, besides nobody will ever visualize 1M items...
            $komponent->passLocale();
        } 

        $q = $komponent->query();

        if ($q instanceof LaravelModel || $q instanceof LaravelEloquentBuilder || $q instanceof LaravelRelation) {
            $komponent->model = $q->getModel();

            $komponent->keyName = $komponent->model->getKeyName();

            $komponent->query = new EloquentQuery($q, $komponent);
        } elseif ($q instanceof LaravelQueryBuilder) {
            $komponent->keyName = $komponent->keyName; //TODO: try to guess table primarykey

            $komponent->query = new DatabaseQuery($q, $komponent);
        } elseif (Util::isCollection($q) || is_array($q) || $q === null) {
            $komponent->query = new CollectionQuery($q, $komponent);
        } else {
            throw new BadQueryDefinitionException(class_basename($komponent));
        }
    }
}
