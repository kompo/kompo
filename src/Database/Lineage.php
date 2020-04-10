<?php

namespace Kompo\Database;

use RuntimeException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lineage
{
    /**
     * Get the model's eloquent relation from a string.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $relationName
     * 
     * @return Eloquent\Relationship|null
     */
    public static function findRelation($model, $relationName)
    {
        return (method_exists($model, $relationName)  && (($relation = $model->{$relationName}()) instanceOf Relation)) ? $relation : null;
    }

    /**
     * Get the model's eloquent relation or fail if not found.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param  string $relation
     * 
     * @return Eloquent\Relationship|null
     */
    public static function findOrFailRelation($model, $relation)
    {
        if(!($relation = static::findRelation($model, $relation)))
            throw new RuntimeException("The relation {$relation} was not found on Model {$model}");

        return $relation;
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
        return Lineage::findOrFailRelation($model, $relationName)->getRelated()->newInstance();
    }

    /**
     * Removes the model constraints from the relation to retrieve all the possible related instances.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $name
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public static function findOrFailRelationNoConstraints($model, $name)
    {
        return Relation::noConstraints(function () use ($model, $name) {
            return static::findOrFailRelation($model, $name);
        });
    }

    /**
     * Checks if the model has a One to One relation.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $relationName
     * 
     * @return Boolean
     */
    public static function isOneToOne($model, $relationName)
    {
        $relation = static::findRelation($model, $relationName);
        return $relation instanceof BelongsTo || $relation instanceof HasOne || $relation instanceof MorphOne;
    }



    public static function fillsAfterSave($mainModel, $requestName)
    {
        return ($relation = static::findRelation($mainModel, $requestName)) && !($relation instanceof BelongsTo); //morphTo is a belongsTo
    }
}

