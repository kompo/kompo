<?php 

namespace Kompo\Database;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kompo\Core\RequestData;
use Kompo\Database\DatabaseQuery;
use Kompo\Database\Lineage;
use Kompo\Database\NameParser;

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
        $value = RequestData::get($field->name);
        $operator = $this->inferBestOperator($field);

        $this->query = $this->handleEloquentFilter($this->query, $this->model, $field->name, $operator, $value);

        //dd($this->query->toSql(), $this->query->getBindings());
        //parent::handleFilter($field);
    }

    protected function handleEloquentFilter($q, $model, $recursiveName, $operator, $value, $table = null)
    {
        $firstTerm = NameParser::firstTerm($recursiveName);
        $relation = Lineage::findRelation($model, $firstTerm);

        if(!$relation)
            return $this->applyWhere($q, $firstTerm, $operator, $value, $table);

        $table = $relation->getRelated()->getTable();
        $model = $relation->getRelated();
        $secondTerm = NameParser::secondTerm($recursiveName) ?: $model->getKeyName();

        $whereHasMethod = $relation instanceof MorphTo ? 'whereHasMorph' : 'whereHas';

        return $q->{$whereHasMethod}($firstTerm, function($subquery) use($model, $secondTerm, $operator, $value, $table){

            $this->handleEloquentFilter($subquery, $model, $secondTerm, $operator, $value, $table);

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