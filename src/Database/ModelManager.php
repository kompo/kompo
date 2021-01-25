<?php

namespace Kompo\Database;

use Kompo\Core\Util;
use Kompo\Database\Lineage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ModelManager
{

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
    public static function fillAttribute($model, $name, $value, $extraAttributes = null, $morphToModel = null)
    {
        $relation = Lineage::findRelation($model, $name);

        if($relation instanceOf BelongsTo){

            if($relation instanceOf MorphTo)
                $model->{$relation->getMorphType()} = $value ? $morphToModel : null;

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
        static::setCreatedUpdatedBy($model);

        $model->save();
    }

    /**
     * Saves a HasOne model with it's attributes to the DB.
     *
     * @param Illuminate\Database\Eloquent\Model $mainModel
     * @param Illuminate\Database\Eloquent\Model $hasOneModel
     * 
     * @return boolean
     */
    public static function saveOneToOne($mainModel, $relation)
    {
        static::setCreatedUpdatedBy($mainModel->{$relation});

        $mainModel->{$relation}()->save($mainModel->{$relation});
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
        $relation = Lineage::findOrFailRelation($model, $name);

        if($relation instanceOf HasMany || $relation instanceOf MorphMany){

            $value = Util::merge($value, $extraAttributes);
            static::saveMany($relation, $name, $value);

        }elseif($relation instanceOf HasOne || $relation instanceOf MorphOne){

            $value = Util::merge($value, $extraAttributes); //merges collections
            static::saveOne($relation, $name, $value);

        }elseif ($relation instanceOf BelongsToMany || $relation instanceOf MorphToMany){ //morphedByMany is a MorphToMany too

            if($value && $extraAttributes && count($extraAttributes))
                $value = array_combine($value, array_fill(0, count($value), $extraAttributes));

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

    protected static function setCreatedUpdatedBy($model)
    {
        if(defined(get_class($model).'::CREATED_BY') && !$model->getKey() && $model::CREATED_BY && auth()->check())
            $model->{$model::CREATED_BY} = auth()->user()->id;
        if(defined(get_class($model).'::UPDATED_BY') && $model::UPDATED_BY && auth()->check())
            $model->{$model::UPDATED_BY} = auth()->user()->id;        
    }


    /* To unguard attributes */
    protected static function saveOne($relation, $column, $attributes)
    {
        if(!$attributes) return;

        $related = $relation->getRelated()->newInstance();
        foreach ($attributes as $key => $attribute) {
            $related->{$key} = $attribute;
        }
        $relation->save($related);
    }

    protected static function saveMany($relation, $column, $arrayOfAttributes)
    {
        if(!$arrayOfAttributes) return;
        
        foreach ($arrayOfAttributes as $attributes) {
            static::saveOne($relation, $column, $attributes);
        }
    }
}

