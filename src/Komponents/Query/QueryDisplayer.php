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

        static::enrichSlicerOptions($query);

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
            $komponent->headers = collect($komponent->headers())->filter()->values();
        }

        if (method_exists($komponent, 'footers')) {
            $komponent->footers = collect($komponent->footers())->filter()->values();
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

    /**
     * Enrich Th slicer options by querying distinct values from the database.
     * Only runs for Th elements that have a slicerName but no slicerOptions.
     *
     * @param Kompo\Query $komponent
     *
     * @return void
     */
    protected static function enrichSlicerOptions($komponent)
    {
        if (!isset($komponent->headers) || !$komponent->headers) {
            return;
        }

        $queryWrapper = $komponent->query;
        $isDatabase = $queryWrapper instanceof DatabaseQuery;

        foreach ($komponent->headers as $header) {
            if (!($header instanceof \Kompo\Th)) {
                continue;
            }

            $slicerName = $header->config('slicerName');
            $slicerOptions = $header->config('slicerOptions');

            if (!$slicerName || !empty($slicerOptions)) {
                continue;
            }

            if ($isDatabase) {
                $values = static::getDistinctValues($komponent, $queryWrapper, $slicerName);
            } else {
                $values = collect($queryWrapper->getQuery())
                    ->pluck($slicerName)
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values();
            }

            $options = $values
                ->mapWithKeys(function ($value) {
                    return [$value => $value];
                })
                ->all();

            $header->config(['slicerOptions' => $options]);
        }
    }

    /**
     * Get distinct values for a slicer column, supporting dot notation for relationships.
     *
     * @param Kompo\Query                 $komponent
     * @param Kompo\Database\DatabaseQuery $queryWrapper
     * @param string                       $slicerName
     *
     * @return \Illuminate\Support\Collection
     */
    protected static function getDistinctValues($komponent, $queryWrapper, $slicerName)
    {
        $parts = explode('.', $slicerName);
        $q = clone $queryWrapper->getQuery();

        if (count($parts) == 2 && $komponent->model) {
            $relationName = $parts[0];
            $column = $parts[1];

            $relation = \Kompo\Database\Lineage::findRelation($komponent->model, $relationName);

            if ($relation) {
                $modelTable = $komponent->model->getTable();
                $relationTable = $relation->getRelated()->getTable();
                $selectColumn = $relationTable.'.'.$column;

                if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
                    $q->select($selectColumn)
                        ->leftJoin(
                            $relationTable,
                            $relationTable.'.'.$relation->getRelated()->getKeyName(),
                            '=',
                            $modelTable.'.'.$relation->getForeignKeyName()
                        );
                } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                    $pivotTable = $relation->getTable();

                    $q->select($selectColumn)
                        ->leftJoin(
                            $pivotTable,
                            $pivotTable.'.'.$relation->getForeignPivotKeyName(),
                            '=',
                            $modelTable.'.'.$komponent->model->getKeyName()
                        )
                        ->leftJoin(
                            $relationTable,
                            $relationTable.'.'.$relation->getRelatedKeyName(),
                            '=',
                            $pivotTable.'.'.$relation->getRelatedPivotKeyName()
                        );
                } else {
                    $q->select($selectColumn)
                        ->leftJoin(
                            $relationTable,
                            $relationTable.'.'.$relation->getRelated()->getKeyName(),
                            '=',
                            $modelTable.'.'.$komponent->model->getKeyName()
                        );
                }

                return $q->distinct()->pluck($selectColumn)->filter()->sort()->values();
            }
        }

        return $q->select($slicerName)->distinct()->pluck($slicerName)->filter()->sort()->values();
    }
}
