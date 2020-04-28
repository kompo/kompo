<?php

namespace Kompo\Komposers\Query;

use Illuminate\Database\Eloquent\Builder as LaravelEloquentBuilder;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\Relations\Relation as LaravelRelation;
use Illuminate\Database\Query\Builder as LaravelQueryBuilder;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\CardGenerator;
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
     * @param Kompo\Query  $query
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

        return $query;
    }

    /**
     * For browsing: Filters, Sorts, Paginates Cards
     *
     * @param Kompo\Query  $query
     *
     * @return Illuminate\...\Paginator
     */
    public static function browseCards($query)
    {
        QueryFilters::prepareFiltersForAction($query);
        
        AuthorizationGuard::mainGate($query);

        ValidationManager::validateRequest($query);

        QueryFilters::filterAndSort($query);

        CardGenerator::getTransformedCards($query);

        return $query->query;
    }

    /**
     * Initialize the browsing, ordering and optionnally set the headers of a table query.
     * 
     * @param Kompo\Query  $query
     *
     * @return void
     */
    protected static function prepareConfigurations($komposer)
    {
        $komposer->data([
            'browseUrl' => RouteFinder::getKompoRoute()
        ]);

        $komposer->noItemsFound = __($komposer->noItemsFound);

        // $this->configureOrdering(); //TODO: on hold for now

        if(method_exists($komposer, 'headers'))
            $komposer->headers = collect($komposer->headers())->filter();
    }

    /**
     * Prepare the underlying query that will fetch the komposer cards.
     * 
     * @param Kompo\Query  $komposer
     *
     * @return void
     */
    public static function prepareQuery($komposer)
    {
        $q = $komposer->query();

        if($q instanceOf LaravelModel || $q instanceOf LaravelEloquentBuilder  || $q instanceOf LaravelRelation){

            $komposer->model = $q->getModel();

            $komposer->keyName = $komposer->model->getKeyName();

            $komposer->query = new EloquentQuery($q, $komposer);

        }elseif($q instanceOf LaravelQueryBuilder){

            $komposer->keyName = $komposer->keyName; //TODO: try to guess table primarykey
            
            $komposer->query = new DatabaseQuery($q, $komposer);
            
        }elseif(Util::isCollection($q) || is_array($q) || $q === null){

            $komposer->query = new CollectionQuery($q, $komposer);

        }else{
        
            throw new BadQueryDefinitionException(class_basename($komposer));

        }
    }

}