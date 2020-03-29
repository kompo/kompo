<?php 

namespace Kompo\Database;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kompo\Core\RequestData;
use Kompo\Database\DatabaseQuery;
use Kompo\Database\Lineage;

class EloquentQuery extends DatabaseQuery
{

    protected $model;

    /**
     * Constructs a Vuravel\Catalog\EloquentQuery object
     *
     * @param  array $components
     * @return void
     */
    public function __construct($query, $catalog)
    {
        parent::__construct($query, $catalog);

        $this->model = $this->catalog->model;
    }

    public function handleFilter($field)
    {
        if(Lineage::findRelation($this->model, $field->name)){
            $this->eloquentFilter($field);
        }else{
            parent::handleFilter($field);
        }
    }

    public function eloquentFilter($field)
    {
        $relation = Lineage::findRelation($this->model, $field->name);
        $value = RequestData::fieldShouldFilter($field);
        $operator = $this->inferBestOperator($field);

        //if($relation instanceOf BelongsTo){
        //    $this->query = $this->applyWhere($this->query, $relation->getForeignKeyName(), $operator, $value);
        //}else{
            $filterKey = explode('.', $field->data('filterKey'), 2);

            $name = count($filterKey) == 2 ? $filterKey[1] : $relation->getRelated()->getKeyName();
            $table = $relation->getRelated()->getTable();

            $this->query = $this->applyEloquentWhere($this->query, $field->name, $name, $operator, $value, $table);
        //}
    }

    protected function applyEloquentWhere($q, $relation, $name, $operator, $value, $table = null)
    {
        return $q->whereHas($relation, function($subquery) use($name, $operator, $value, $table){
            $name = explode('.', $name);
            if(count($name) == 1){
                return $this->applyWhere($subquery, ($table? ($table.'.') : '').$name[0], $operator, $value);
            }else{
                return $this->applyEloquentWhere($subquery, $name[0], $name[1], $operator, $value);
            }
        });
    }

    public function sortBy($sort)
    {
        $sort = explode(':', $sort);
        $sortBy = explode('.', $sort[0]);

        if(Lineage::findRelation($this->model, $sortBy[0])){
            if(count($sortBy) == 1)
                abort(500, 'Please define the column you want to sort on relationship '.$sortBy[0].' using the syntax: relationship.column_name:direction');
            $this->eloquentSort($sortBy[0], $sortBy[1], count($sort) == 2 ? $sort[1] : 'ASC');
        }else{
            $this->attributeSort(implode(':', $sort));
        }
    }

    public function eloquentSort($relation, $column, $direction)
    {
        $relation = Lineage::findRelation($this->model, $relation);

        if($relation instanceOf BelongsTo)
        {
            $modelTable = $this->model->getTable();
            $relationTable = $relation->getRelated()->getTable();

            $modelKey = $relation->getForeignKeyName();
            $relationKey = $relation->getRelated()->getKeyName();

            $this->query->selectRaw($modelTable.'.*')
                ->leftJoin($relationTable, $relationTable.'.'.$relationKey, '=', $modelTable.'.'.$modelKey)->orderBy($relationTable.'.'.$column, $direction);

        }else if($relation instanceOf HasOne){

        }else if($relation instanceOf BelongsToMany){
            $modelTable = $this->model->getTable();
            $pivotTable = $relation->getTable();
            $relationTable = $relation->getRelated()->getTable();

            $modelKey = $this->model->getKeyName();
            $foreignPivotKey = $relation->getForeignPivotKeyName();
            $relatedPivotKey = $relation->getRelatedPivotKeyName();
            $relationKey = $relation->getRelatedKeyName();

            $this->query->selectRaw($modelTable.'.*')
                ->leftJoin($pivotTable, $pivotTable.'.'.$foreignPivotKey, '=', $modelTable.'.'.$modelKey)
                ->leftJoin($relationTable, $relationTable.'.'.$relationKey, '=', $pivotTable.'.'.$relatedPivotKey)->orderBy($relationTable.'.'.$column, $direction);
        }
    }


}