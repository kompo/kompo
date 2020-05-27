<?php 

namespace Kompo\Database;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kompo\Database\DatabaseQuery;
use Kompo\Database\Lineage;
use Kompo\Database\NameParser;
use Kompo\Exceptions\NotFoundMorphToModelException;
use Kompo\Komponents\FormField;

class EloquentQuery extends DatabaseQuery
{
    /**
     * { item_description }
     */
    protected $model;

    /**
     * Constructs a Kompo\Database\EloquentQuery object
     *
     * @param  array $komponents
     * @return void
     */
    public function __construct($query, $komposer)
    {
        parent::__construct($query, $komposer);

        $this->model = $this->komposer->model;
    }

    /**
     * { function_description }
     *
     * @param      <type>  $field  The field
     */
    public function handleFilter($field)
    {        
        $value = $this->getFilterValueFromRequest($field->name);
        $operator = $this->inferBestOperator($field);

        $morphToModel = FormField::getConfig($field, 'morphToModel');

        $this->query = $this->handleEloquentFilter($this->query, $this->model, $field->name, $operator, $value, $morphToModel);

        //dd($this->query->toSql(), $this->query->getBindings());
        //parent::handleFilter($field);
    }

    /**
     * Handles filtering for all relationships except MorphTo.
     *
     * @param  Illuminate\Database\Eloquent\Builder $q              
     * @param  Illuminate\Database\Eloquent\Model   $morphToModel  
     * @param  string                               $recursiveName
     * @param  string                               $operator
     * @param  mixed                                $value 
     * @param  Illuminate\Database\Eloquent\Model   $morphToModel
     *
     * @return Illuminate\Database\Eloquent|Builder
     */
    protected function handleEloquentFilter($q, $model, $recursiveName, $operator, $value, $morphToModel = null)
    {
        $firstTerm = NameParser::firstTerm($recursiveName);
        $relation = Lineage::findRelation($model, $firstTerm);

        if(!$relation)
            return $this->applyWhere($q, $firstTerm, $operator, $value, $model->getTable());

        if($relation instanceof MorphTo)
            return $this->handleMorphToFilter($q, $morphToModel, $recursiveName, $operator, $value);

        $model = $relation->getRelated();

        $secondTerm = NameParser::secondTerm($recursiveName) ?: $model->getKeyName();

        return $q->whereHas($firstTerm, function($subquery) use($model, $secondTerm, $operator, $value){

            $this->handleEloquentFilter($subquery, $model, $secondTerm, $operator, $value);

        });
    }

    /**
     * Handles filtering by a MorphTo relationship. This one is different from others 
     * because you need to specify the MorphTo Model since it cannot be infered from the relationship.
     *
     * @param  Illuminate\Database\Eloquent|Builder $q              
     * @param  Illuminate\Database\Eloquent\Model   $morphToModel  
     * @param  string                               $recursiveName
     * @param  string                               $operator
     * @param  mixed                                $value 
     *
     * @throws \Kompo\Exceptions\NotFoundMorphToModelException
     *
     * @return Illuminate\Database\Eloquent|Builder
     */
    protected function handleMorphToFilter($q, $morphToModel, $recursiveName, $operator, $value)
    {
        $firstTerm = NameParser::firstTerm($recursiveName);

        if(!$morphToModel)
            throw new NotFoundMorphToModelException($firstTerm);

        $model = new $morphToModel();       

        $secondTerm = NameParser::secondTerm($recursiveName) ?: $model->getKeyName();

        return $q->whereHasMorph($firstTerm, [$morphToModel], function($subquery) use($model, $secondTerm, $operator, $value){
            $this->handleEloquentFilter($subquery, $model, $secondTerm, $operator, $value);
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