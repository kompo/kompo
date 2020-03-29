<?php

namespace Kompo\Database;

use Kompo\Database\Lineage;
use Kompo\Exceptions\NotOneToOneRelationException;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EloquentField
{
    /**
     * Gets the appropriate model and it's attribute from the field's name.
     * Its goal is to handle nested One to One relations with dot notation.
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @param  string  $initialName
     *
     * @throws \Kompo\Exceptions\NotOneToOneRelationException  (description)
     *
     * @return array
     */
    public static function parseName($model, $initialName)
    {
        $name = explode('.', $initialName);

        while ( count($name) > 1) {

            $relationName = $name[0];

            if(!Lineage::isOneToOne($model, $relationName))
                throw new NotOneToOneRelationException($initialName, $relationName);

            $model = $model->{$relationName} ?: $model->{$relationName}()->getRelated()->newInstance();
            array_shift($name);
        }

        return [$model, $name[0]];
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
     * Gets all the models that could be related.
     * This is used for Selects and works only with BelongsTo and BelongsToMany.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $relation
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRelatedCandidates($model, $initialName, $morphToModel)
    {
        list($model, $name) = static::parseName($model, $initialName);

        if($model->$name() instanceOf MorphTo){ //should be before Relation::noConstraints because it changes the related class...
            $newInstance = $model->newInstance();
            $newInstance->{$model->$name()->getMorphType()} = $morphToModel; //setting the related model
            $relationQuery = $newInstance->$name()->getBaseQuery();
            array_shift($relationQuery->wheres); //removing a join that stays after noConstraints for some reason...
            return $relationQuery->get();
        }

        $relation = Lineage::findOrFailRelationNoConstraints($model, $name);

        if($relation instanceOf BelongsToMany){
            $relationQuery = $relation->getBaseQuery();
            array_shift($relationQuery->joins); //removing a join that stays after noConstraints for some reason...
            return $relationQuery->get();
        }

        return $relation->get();
    }

    /**
     * If the field should fill before saving the model.
     *
     * @param Illuminate\Database\Eloquent\Model  $model
     * @param string                              $name
     *
     * @return Boolean
     */
    public static function fillsBeforeSave($model, $name)
    {
        return !static::fillsAfterSave($model, $name);
    }

    /**
     * If the field should fill after saving the model.
     *
     * @param Illuminate\Database\Eloquent\Model  $model
     * @param string                              $name
     *
     * @return Boolean
     */
    public static function fillsAfterSave($model, $name)
    {
        return ($relation = Lineage::findRelation($model, $name)) && !($relation instanceof BelongsTo); //morphTo is a belongsTo
    }

    /**
     * Gets the eloquent configuration of the field.
     *
     * @param Kompo\Komponents\Field $field       The field
     * @param string                 $configKey   The desired configuration key.
     *
     * @return  mixed
     */
    public static function getConfig($field, $configKey)
    {
        return $field->_kompo('eloquent')[$configKey];
    }

    /**
     * Sets the eloquent configuration of the field.
     *
     * @param Kompo\Komponents\Field $field        The field
     * @param string                 $configKey    The desired configuration key.
     * @param mixed                  $configValue  The desired configuration value.
     *
     * @return  self
     */
    public static function setConfig($field, $configKey, $configValue)
    {
        return $field->_kompo('eloquent', [$configKey => $configValue]);
    }

}