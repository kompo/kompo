<?php

namespace Kompo\Eloquent;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne, HasMany, MorphOne, MorphMany, MorphTo, MorphToMany};
use Kompo\Exceptions\NotOneToOneRelationException;
use Kompo\Exceptions\RelationNotFoundException;
use Kompo\Form;
use Kompo\Utilities\Arr;
use RuntimeException;
use Schema;

class ModelManager
{
    /**
     * Initialize or find the model (if komposer linked to a model).
     *
     * @param Kompo\Komposer\Komposer $komposer
     * @param Illuminate\Database\Eloquent\Model|null $model
     * @return void
     */
	public static function fetchConfig($komposer, $model = null)
	{
        if(is_null($model))
            return;

        $komposer->model = $model instanceof Model ? $model : $model::findOrNew($komposer->modelKey());
        $komposer->modelKey($komposer->model()->getKey()); //set if it wasn't (ex: dynamic model set in created() phase)

        return $komposer->model;
	}

    /**
     * { function_description }
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @param  string  $initialName
     *
     * @throws \Kompo\Exceptions\NotOneToOneRelationException  (description)
     *
     * @return array
     */
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
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $relation
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
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $relationName
     * 
     * @return Eloquent\Relationship|null
     */
    public static function findRelation($model, $relationName)
    {
        return method_exists($model, $relationName) ? $model->{$relationName}() : null;
    }

    /**
     * Gets a related instance for a specific relation.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $relationName
     * 
     * @return Eloquent\Relationship|null
     */
    public static function findOrFailRelated($model, $relationName)
    {
        return static::findOrFailRelation($model, $relationName)->getRelated()->newInstance();
    }

    /**
     * Gets the value according to the name and optional select columns.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param  string $name
     * 
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
     * @param Illuminate\Database\Eloquent\Model $model
     * @param  string $name
     * 
     * @return boolean
     */
    public static function fillAttribute($model, $name, $value, $extraAttributes = null)
    {
        if(($relation = static::findRelation($model, $name)) && $relation instanceOf BelongsTo){

            $model->{$relation->getForeignKeyName()} = $value;
            static::fillExtraAttributes($model->{$name}, $extraAttributes, true);
            $model->load($name); //reload fresh belongsTo

        }else{
            $model->{$name} = $value;
            static::fillExtraAttributes($model, $extraAttributes);
        }
    }

    /**
     * Saves the model with it's attributes to the DB.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * 
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
     * @param Illuminate\Database\Eloquent\Model $model
     * @param  string $name
     * 
     * @return boolean
     */
    public static function saveAndLoadRelation($model, $name, $value, $extraAttributes = null)
    {
        $relation = static::findOrFailRelation($model, $name);

        if($relation instanceOf HasMany || $relation instanceOf MorphMany){

            $value = Arr::merge($value, $extraAttributes);
            static::saveMany($relation, $name, $value);

        }elseif($relation instanceOf HasOne || $relation instanceOf MorphOne){

            $value = Arr::merge($value, $extraAttributes); //merges collections
            static::saveOne($relation, $name, $value);

        }elseif ($relation instanceOf BelongsToMany || $relation instanceOf MorphToMany){

            if($value && $extraAttributes && count($extraAttributes))
                $value = array_combine($value, array_fill(0, count($value), $extraAttributes));

            $relation->sync($value);

        }
        //elseif ($relation instanceOf BelongsTo) { do nothing-- handled in attributes. 
            

        $model->load($name);
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
     * @param Illuminate\Database\Eloquent\Model $model
     * @param  string $relation
     * 
     * @return Eloquent\Relationship|null
     */
    protected static function findOrFailRelation($model, $relation)
    {
        if(!($relation = static::findRelation($model, $relation)))
            throw new RuntimeException("The relation {$relation} was not found on Model {$model}");

        return $relation;
    }

    /**
     * Fill the desired additional attributes.
     *
     * @param Illuminate\Database\Eloquent\Model $model  The model
     * @param array $extraAttributes  The extra attributes
     */
    protected static function fillExtraAttributes($model, $extraAttributes, $saveIfBelongsTo = false)
    {
        if($model && $extraAttributes && count($extraAttributes)){
            collect($extraAttributes)->each(function($val, $key) use($model){
                $model->{$key} = $val;
            });
            if($saveIfBelongsTo)
                $model->save();
        }
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

