<?php

namespace Kompo\Database;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Kompo\Core\Util;

class ModelManager
{
    /**
     * Gets the value according to the name and optional select columns.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $name
     * @param closure                            $relationScope
     *
     * @return mixed
     */
    public static function getValueFromDb($model, $name, $relationScope = null)
    {
        $value = $relationScope ? 
            $value = $model->{$name}()->where($relationScope)->get(): //only for a special case of MultiForm
            $model->{$name};

        if ($value instanceof Collection && $value->isEmpty()) {
            $value = null;
        }

        return $value;
    }

    /**
     * Fill the model's attribute value according to the field's name.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $name
     *
     * @return bool
     */
    public static function fillAttribute($model, $name, $value, $extraAttributes = null, $morphToModel = null)
    {
        $relation = Lineage::findRelation($model, $name);

        if ($relation instanceof BelongsTo) {
            if ($relation instanceof MorphTo) {
                $model->{$relation->getMorphType()} = $value ? $morphToModel : null;
            }
            $model->{$relation->getForeignKeyName()} = $value;

            $model->load($name); //reload fresh belongsTo

            static::fillExtraAttributes($model->{$name}, $extraAttributes, true);

            $model->load($name); //reload fresh belongsTo

        } else {
            $model->{$name} = $value;
            static::fillExtraAttributes($model, $extraAttributes);
        }
    }

    /**
     * Saves the model with it's attributes to the DB.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public static function saveAttributes($model)
    {
        static::setCreatedUpdatedBy($model);

        $model->save();
    }

    /**
     * Saves a HasOne model with it's attributes to the DB.
     *
     * @param Illuminate\Database\Eloquent\Model $mainModel
     * @param Illuminate\Database\Eloquent\Model $hasOneModel
     *
     * @return bool
     */
    public static function saveOneToOneOrDelete($mainModel, $relation, $names, $deleteOneToOneIfEmpty = false)
    {
        $nonEmptyAttributes = collect($names)->filter(
            fn ($name) => $mainModel->{$relation}->{$name}
        )->count();

        if ($nonEmptyAttributes) {
            static::setCreatedUpdatedBy($mainModel->{$relation});

            $mainModel->{$relation}()->save($mainModel->{$relation});
        } else {
            if ($mainModel->{$relation}->exists && $deleteOneToOneIfEmpty) {
                $mainModel->{$relation}->delete();
            }
        }
    }

    /**
     * Fill the model's relations value according to the field's name.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $name
     *
     * @return bool
     */
    public static function saveAndLoadRelation($model, $name, $value, $extraAttributes = null)
    {
        $relation = Lineage::findOrFailRelation($model, $name);

        if ($relation instanceof HasMany || $relation instanceof MorphMany) {
            $value = Util::merge($value, $extraAttributes);
            static::saveMany($relation, $name, $value);
        } elseif ($relation instanceof HasOne || $relation instanceof MorphOne) {
            $value = Util::merge($value, $extraAttributes); //merges collections
            static::saveOne($relation, $name, $value);
        } elseif ($relation instanceof BelongsToMany || $relation instanceof MorphToMany) { //morphedByMany is a MorphToMany too

            if ($value && $extraAttributes && count($extraAttributes)) {
                $value = array_combine($value, array_fill(0, count($value), $extraAttributes));
            }

            $relation->sync($value);
        }
        //elseif ($relation instanceOf BelongsTo || MorphTo) { do nothing-- handled in attributes.

        $model->load($name);
    }

    public static function getStoragePath($model, $column)
    {
        return $model->getConnectionName().'/'.$model->getTable().'/'.$column;
    }

    /********** PROTECTED ************/

    /**
     * Fills the desired additional attributes.
     *
     * @param Illuminate\Database\Eloquent\Model $model           The model
     * @param array                              $extraAttributes The extra attributes
     */
    protected static function fillExtraAttributes($model, $extraAttributes, $saveIfBelongsTo = false)
    {
        if ($model && $extraAttributes && count($extraAttributes)) {
            collect($extraAttributes)->each(function ($val, $key) use ($model) {
                $model->{$key} = $val;
            });
            if ($saveIfBelongsTo) {
                $model->save();
            }
        }
    }

    protected static function setCreatedUpdatedBy($model)
    {
        if (defined(get_class($model).'::CREATED_BY') && !$model->getKey() && $model::CREATED_BY && auth()->check()) {
            $model->{$model::CREATED_BY} = auth()->user()->id;
        }
        if (defined(get_class($model).'::UPDATED_BY') && $model::UPDATED_BY && auth()->check()) {
            $model->{$model::UPDATED_BY} = auth()->user()->id;
        }
    }

    /* To unguard attributes */
    protected static function saveOne($relation, $column, $attributes)
    {
        if (!$attributes) {
            return;
        }

        $related = $relation->getRelated()->newInstance();
        foreach ($attributes as $key => $attribute) {
            $related->{$key} = $attribute;
        }
        $relation->save($related);
    }

    protected static function saveMany($relation, $column, $arrayOfAttributes)
    {
        if (!$arrayOfAttributes) {
            return;
        }

        foreach ($arrayOfAttributes as $attributes) {
            static::saveOne($relation, $column, $attributes);
        }
    }
}
