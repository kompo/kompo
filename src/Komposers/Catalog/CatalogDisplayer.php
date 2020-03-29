<?php

namespace Kompo\Komposers\Catalog;

use Illuminate\Database\Eloquent\Builder as LaravelEloquentBuilder;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\Relations\Relation as LaravelRelation;
use Illuminate\Database\Query\Builder as LaravelQueryBuilder;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\CardPaginator;
use Kompo\Core\SessionStore;
use Kompo\Core\Util;
use Kompo\Core\ValidationManager;
use Kompo\Database\CollectionQuery;
use Kompo\Database\DatabaseQuery;
use Kompo\Database\EloquentQuery;
use Kompo\Exceptions\BadQueryDefinitionException;
use Kompo\Komposers\Catalog\CatalogBrowser;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\RouteFinder;

class CatalogDisplayer extends CatalogBrowser
{
    public static function displayFiltersAndCards($catalog)
    {
        AuthorizationGuard::checkBoot($catalog);

        KomposerManager::created($catalog);

        static::prepareCatalog($catalog);

        static::prepareQuery($catalog);

        CatalogBrowser::prepareFiltersForDisplay($catalog);

        CardPaginator::transformItems($catalog);

        ValidationManager::addRulesToKomposer($catalog->rules(), $catalog); //for Front-end validations TODO:

        SessionStore::saveKomposer($catalog); 

        return $catalog;
    }

    /**
     * Initialize the browsing, ordering and optionnally set the columns of a table catalog.
     *
     * @return void
     */
    protected static function prepareCatalog($catalog)
    {
        $catalog->browseUrl = RouteFinder::getKompoRoute(); //--> TODO: move to data

        $catalog->noItemsFound = __($catalog->noItemsFound);

        // $this->configureOrdering(); //TODO: on hold for now

        if(method_exists($catalog, 'columns'))
            $catalog->columns = collect($catalog->columns())->filter(); //--> TODO: move to data
    }

    /**
     * Prepare the query for the catalog items.
     *
     * @return void
     */
    public static function prepareQuery($catalog)
    {
        $q = $catalog->query();

        if($q instanceOf LaravelModel || $q instanceOf LaravelEloquentBuilder  || $q instanceOf LaravelRelation){

            $catalog->model = $q->getModel();

            $catalog->keyName = $catalog->model->getKeyName();

            $catalog->query = new EloquentQuery($q, $catalog);

        }elseif($q instanceOf LaravelQueryBuilder){

            $catalog->keyName = $catalog->keyName; //TODO: try to guess table primarykey
            
            $catalog->query = new DatabaseQuery($q, $catalog);
            
        }elseif(Util::isCollection($q) || is_array($q) || $q === null){

            $catalog->query = new CollectionQuery($q, $catalog);

        }else{
        
            throw new BadQueryDefinitionException(class_basename($catalog));

        }
    }

}