<?php

namespace Kompo\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne, HasMany, MorphOne, MorphMany, MorphTo, MorphToMany};
use Kompo\Exceptions\NotOneToOneRelationException;
use Kompo\Exceptions\RelationNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Kompo\Form;
use RuntimeException;
use BadMethodCallException;
use Schema;
use Illuminate\Database\Eloquent\Collection;

class ModelManager
{
    /**
     * Initialize or find the model (if komposer linked to a model).
     *
     * @return void
     */
	public static function fetchConfig($komposer, $model = null)
	{
        if(is_null($model))
            return;

        $komposer->model = $model instanceof Model ? $model : $model::findOrNew($komposer->modelKey());
        $komposer->modelKey($komposer->model()->getKey()); //set if it wasn't (ex: dynamic model set in created() phase)

        $komposer->_kompo('columns', Schema::connection($komposer->model->connection)->getColumnListing($komposer->model->table));

        return $komposer->model;
	}


    public static function parseFromFieldName($model, $initialName)
    {
        $name = explode('.', $initialName);

        while ( count($name) > 1) {

            $relationName = $name[0];

            if(!static::isOneToOne($model, $relationName))
                throw new NotOneToOneRelationException($initialName, $relationName);

            $model = $model->{$relationName} ?: $model->{$relationName}()->getRelated()->newInstance();
            array_shift($name);
        }

        return [$model, $name[0]];
    }

    
    /**
     * Gets all the models that could be related.
     * This is used for Selects and works only with BelongsTo and BelongsToMany.
     *
     * @param  string $relation
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRelatedCandidates($model, $initialName)
    {
        list($model, $name) = static::parseFromFieldName($model, $initialName);

        $relation = Relation::noConstraints(function () use ($model, $name) {
            try {
                return $model->newInstance()->$name();
            } catch (BadMethodCallException $e) {
                throw new RelationNotFoundException(class_basename($model), $name);
            }
        });

        if($relation instanceOf BelongsToMany){
            $relationQuery = $relation->getBaseQuery();
            array_shift($relationQuery->joins);
            return $relationQuery->get();
        }

        return $relation->get();
    }

    public static function fillsBeforeSave($model, $name)
    {
        return !static::fillsAfterSave($model, $name);
    }

    public static function fillsAfterSave($model, $name)
    {
        return ($relation = static::findRelation($model, $name)) && !($relation instanceof BelongsTo);
    }

    public static function getStoragePath($model, $column)
    {
        return $model->getConnectionName().'/'.$model->getTable().'/'.$column;
    }

    /**
     * Get the model's eloquent relation from a string.
     *
     * @param  string $relationName
     * @return Eloquent\Relationship|null
     */
    public static function findRelation($model, $relationName)
    {
        return method_exists($model, $relationName) ? $model->{$relationName}() : null;
    }

    /**
     * Gets a related instance for a specific relation.
     *
     * @param  string $relationName
     * @return Eloquent\Relationship|null
     */
    public static function findOrFailRelated($model, $relationName)
    {
        return static::findOrFailRelation($model, $relationName)->getRelated()->newInstance();
    }

    /**
     * Gets the value according to the name and optional select columns.
     *
     * @param  string $name
     * @param  array $select
     * @return mixed
     */
    public static function getValueFromDb($model, $name)
    {
        $value = $model->{$name};

        if($value instanceOf Collection && $value->isEmpty())
            $value = null;

        return $value;
    }

    /**
     * Fill the model's attribute value according to the field's name.
     *
     * @param  string $name
     * @return boolean
     */
    public static function fillAttribute($model, $name, $value, $extraAttrbutes = null)
    {
        if(($relation = static::findRelation($model, $name)) && $relation instanceOf BelongsTo){
            $model->{$relation->getForeignKeyName()} = $value;
            $belongsTo = $model->{$relation};
            collect($extraAttrbutes)->each(function($val, $key) use($belongsTo){
                $belongsTo->{$key} = $val;
            });
            $belongsTo->save();
        }else{
            $model->{$name} = $value;
            collect($extraAttrbutes)->each(function($val, $key) use($model){
                $model->{$key} = $val;
            });
        }
    }

    /**
     * Saves the model with it's attributes to the DB.
     *
     * @param  string $name
     * @return boolean
     */
    public static function saveAttributes($model)
    {
        if(defined(get_class($model).'::CREATED_BY') && !$model->getKey() && $model::CREATED_BY && auth()->check())
            $model->{$model::CREATED_BY} = auth()->user()->id;
        if(defined(get_class($model).'::UPDATED_BY') && $model::UPDATED_BY && auth()->check())
            $model->{$model::UPDATED_BY} = auth()->user()->id;

        $model->save();
    }

    /**
     * Fill the model's relations value according to the field's name.
     *
     * @param  string $name
     * @return boolean
     */
    public static function fillRelation($model, $name, $value)
    {
        $relation = static::findOrFailRelation($model, $name);

        if($relation instanceOf HasMany || $relation instanceOf MorphMany){

            static::saveMany($relation, $name, $value);

        }elseif($relation instanceOf HasOne || $relation instanceOf MorphOne){

            static::saveOne($relation, $name, $value);

        }elseif ($relation instanceOf BelongsToMany) {
            
            //To review if Pivot has Author...
            /*$relationIds = Arr::pluck($value, 'value');
            $relationIds = array_combine($relationIds, array_fill(0, count($relationIds), ['user_id' => \Auth::user()->id]));*/
            $relation->sync($value);

        }elseif ($relation instanceOf BelongsTo) {
            
            //just delete the old one
            
        }elseif($relation instanceOf MorphToMany){

            $relation->sync($value);

        }
    }




    /********** PROTECTED ************/


    protected static function isOneToOne($model, $relationName)
    {
        $relation = static::findRelation($model, $relationName);
        return $relation instanceof BelongsTo || $relation instanceof HasOne || $relation instanceof MorphOne;
    }

    /**
     * Get the model's eloquent relation or fail.
     *
     * @param  string $relation
     * @return Eloquent\Relationship|null
     */
    protected static function findOrFailRelation($model, $relation)
    {
        if(!($relation = static::findRelation($model, $relation)))
            throw new RuntimeException("The relation {$relation} was not found on Model {$model}");

        return $relation;
    }


    /* Unguarded attributes */
    protected static function saveOne($relation, $column, $attributes)
    {
        if(!$attributes) return;

        $related = $relation->getRelated()->newInstance();
        foreach ($attributes as $key => $attribute) {
            $related->{$key} = $attribute;
        }
        $test = $relation->save($related);
    }
    protected static function saveMany($relation, $column, $arrayOfAttributes)
    {
        if(!$arrayOfAttributes) return;
        
        foreach ($arrayOfAttributes as $attributes) {
            static::saveOne($relation, $column, $attributes);
        }
    }
}

