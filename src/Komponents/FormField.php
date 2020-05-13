<?php

namespace Kompo\Komponents;

use Kompo\Core\LaravelApp;
use Kompo\Core\RequestData;
use Kompo\Core\Util;
use Kompo\Database\Lineage;
use Kompo\Database\ModelManager;
use Kompo\Database\NameParser;
use Kompo\Exceptions\NotOneToOneRelationException;

class FormField
{
    /**
     * Gets a value from the DB to set to the field value.
     * 
     * @param Kompo\Komponents\Field    $field
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     */
    public static function retrieveValueFromModel($field, $model)
    {
        if(!$model || static::getConfig($field, 'ignoresModel'))
            return;

        Util::collect($field->name)->each(function($name, $key) use($field, $model) {

            list($model, $name) = static::parseName($model, $name);

            $value = $field->getValueFromModel($model, $name);

            $value = $field->shouldCastToArray($model, $name) ? Util::decode($value) : $value;

            $field->setOutput($value, $key);

        });      
    }

    /**
     * Returns the stage during which the model should be filled.
     *
     * @param string                    $stage
     * @param Kompo\Komponents\Field    $field
     * @param Kompo\Komposers\Komposer  $komposer
     *
     * @return Boolean
     */
    public static function fillDuringStage($stage, $field, $komposer)
    {
        if(static::checkDoesNotFill($field, $komposer))
            return;

        return Util::collect($field->name)->map(function($requestName, $key) use($stage, $field, $komposer) {

            if(!RequestData::has($requestName))
                return;

            if(NameParser::isNested($requestName)){
                if($stage == 'fillHasMorphOne')
                    return static::fillHasMorphOne($field, $requestName, $key, $komposer->model);
            }else{
                if(Lineage::fillsAfterSave($komposer->model, $requestName)){
                    if($stage == 'fillAfterSave')
                        return static::fillAfterSave($field, $requestName, $key, $komposer->model);
                }else{
                    if($stage == 'fillBeforeSave')
                        return static::fillBeforeSave($field, $requestName, $key, $komposer->model);
                }
            }

        })->filter();
    }

    /**
     * Checks if the field does not fill or is readonly
     * 
     * @param Kompo\Komponents\Field    $field
     * @param Kompo\Komposers\Komposer  $komposer
     *
     * @return     Boolean  
     */
    public static function checkDoesNotFill($field, $komposer)
    {
        $field->checkSetReadonly($komposer);

        return static::getConfig($field, 'doesNotFill') || static::getConfig($field, 'ignoresModel') || $field->data('readOnly');
    }

    /**
     * Gets the value from the request and fills the model's attributes.
     *
     * @param Kompo\Komponents\Field             $field
     * @param string                             $requestName
     * @param Illuminate\Database\Eloquent\Model $model
     * 
     * @return void
     */
    public static function fillBeforeSave($field, $requestName, $key, $model, $name = null)
    {
        $name = $name ?: $requestName;

        if(LaravelApp::isVersion7orHigher() && $field->shouldCastToArray($model, $name))
            $model->mergeCasts([$name => 'array']);

        $value = $field->setAttributeFromRequest($requestName, $name, $model, $key);

        ModelManager::fillAttribute($model, $name, $value, static::getConfig($field, 'extraAttributes'), static::getConfig($field, 'morphToModel'));

        return true;
    }

    /**
     * Gets the value from the request and parses it optionally (see methods overrides).
     *
     * @param Kompo\Komponents\Field             $field
     * @param string                             $requestName
     * @param Illuminate\Database\Eloquent\Model $model
     * 
     * @return void
     */
    public static function fillAfterSave($field, $requestName, $key, $model)
    {
        $value = $field->setRelationFromRequest($requestName, $requestName, $model, $key);

        ModelManager::saveAndLoadRelation($model, $requestName, $value, static::getConfig($field, 'extraAttributes'));
        
        return true;
    }

    /**
     * Gets the value from the request and parses it optionally (see methods overrides).
     *
     * @param Kompo\Komponents\Field             $field
     * @param string                             $requestName
     * @param Illuminate\Database\Eloquent\Model $mainModel
     * 
     * @return void
     */
    public static function fillHasMorphOne($field, $requestName, $key, $mainModel)
    {
        list($model, $name, $relationName) = static::parseName($mainModel, $requestName);

        static::fillBeforeSave($field, $requestName, $key, $model, $name);
        
        return $relationName; //returning the nested model for save
    }

    /**
     * Gets the appropriate model and it's attribute from the field's name.
     * Its goal is to handle nested One to One relations with dot notation.
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @param  string  $requestName
     *
     * @throws \Kompo\Exceptions\NotOneToOneRelationException  (description)
     *
     * @return array
     */
    protected static function parseName($model, $requestName)
    {
        $relationName = null;

        if(!NameParser::isNested($requestName))
            return [
                $model, 
                $requestName, 
                $relationName
            ];

        if(!Lineage::isOneToOne($model, $relationName = NameParser::firstTerm($requestName)))
            throw new NotOneToOneRelationException($requestName, $relationName);

        if(!$model->{$relationName})
            $model->setRelation($relationName, $model->{$relationName}()->getRelated()->newInstance());

        return [
            $model->{$relationName}, 
            NameParser::secondTerm($requestName), 
            $relationName
        ];
    }

    /**
     * Sets the extra attributes.
     *
     * @param Kompo\Komponents\Field $field
     * @param array                  $extraAttributes
     */
    public static function setExtraAttributes($field, $extraAttributes)
    {
        static::setConfig($field, 'extraAttributes', array_replace(static::getConfig($field, 'extraAttributes'), $extraAttributes));
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