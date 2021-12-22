<?php

namespace Kompo\Elements\Managers;

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
     * @param Kompo\Elements\Field             $field
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     */
    public static function retrieveValueFromModel($field, $model)
    {
        if (!$model || static::getConfig($field, 'ignoresModel')) {
            return;
        }

        Util::collect($field->name)->each(function ($name, $key) use ($field, $model) {
            list($model, $name) = static::parseName($model, $name);

            $value = $field->getValueFromModel($model, $name);

            $value = $field->shouldCastToArray($model, $name) ? Util::decode($value) : $value;

            $field->setOutput($value, $key);
        });
    }

    /**
     * Returns the stage during which the model should be filled.
     *
     * @param string                   $stage
     * @param Kompo\Elements\Field   $field
     * @param Kompo\Komponents\Komponent $komponent
     *
     * @return bool
     */
    public static function fillDuringStage($stage, $field, $komponent)
    {
        if (static::checkDoesNotFill($field, $komponent)) {
            return;
        }

        return Util::collect($field->name)->map(function ($requestName, $key) use ($stage, $field, $komponent) {

            /*if(!RequestData::has($requestName)) //Why is this even here? => Hidden fields...hmm
                return;*/

            if (NameParser::isNested($requestName)) {
                list($model, $name, $relationName) = static::parseName($komponent->model, $requestName);

                if (Lineage::fillsAfterSave($model, $name)) {
                    if ($stage == 'fillOneToOneAfterSave') {
                        static::fillAfterSave($field, $requestName, $key, $model, $name);

                        return ['relation' => $relationName, 'name' => $name];
                    }
                } else {
                    if ($stage == 'fillOneToOneBeforeSave') {
                        static::fillBeforeSave($field, $requestName, $key, $model, $name);

                        return ['relation' => $relationName, 'name' => $name];
                    }
                }
            } else {
                if (Lineage::fillsAfterSave($komponent->model, $requestName)) {
                    if ($stage == 'fillAfterSave') {
                        return static::fillAfterSave($field, $requestName, $key, $komponent->model);
                    }
                } else {
                    if ($stage == 'fillBeforeSave') {
                        return static::fillBeforeSave($field, $requestName, $key, $komponent->model);
                    }
                }
            }
        })->filter();
    }

    /**
     * Checks if the field does not fill or is readonly.
     *
     * @param Kompo\Elements\Field   $field
     * @param Kompo\Komponents\Komponent $komponent
     *
     * @return bool
     */
    public static function checkDoesNotFill($field, $komponent)
    {
        $field->checkSetReadonly($komponent);

        return static::getConfig($field, 'doesNotFill') || static::getConfig($field, 'ignoresModel') || $field->config('readOnly');
    }

    /**
     * Gets the value from the request and fills the model's attributes.
     *
     * @param Kompo\Elements\Field             $field
     * @param string                             $requestName
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public static function fillBeforeSave($field, $requestName, $key, $model, $name = null)
    {
        $name = $name ?: $requestName;

        if (LaravelApp::isVersion7orHigher() && $field->shouldCastToArray($model, $name)) {
            $model->mergeCasts([$name => 'array']);
        }

        $value = $field->setAttributeFromRequest($requestName, $name, $model, $key);

        ModelManager::fillAttribute($model, $name, $value, static::getConfig($field, 'extraAttributes'), static::getConfig($field, 'morphToModel'));

        return true;
    }

    /**
     * Gets the value from the request and parses it optionally (see methods overrides).
     *
     * @param Kompo\Elements\Field             $field
     * @param string                             $requestName
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public static function fillAfterSave($field, $requestName, $key, $model, $name = null)
    {
        $name = $name ?: $requestName;

        $value = $field->setRelationFromRequest($requestName, $name, $model, $key);

        ModelManager::saveAndLoadRelation($model, $name, $value, static::getConfig($field, 'extraAttributes'));

        return true;
    }

    /**
     * Gets the appropriate model and it's attribute from the field's name.
     * Its goal is to handle nested One to One relations with dot notation.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $requestName
     *
     * @throws \Kompo\Exceptions\NotOneToOneRelationException (description)
     *
     * @return array
     */
    protected static function parseName($model, $requestName)
    {
        $relationName = null;

        if (!NameParser::isNested($requestName)) {
            return [
                $model,
                $requestName,
                $relationName,
            ];
        }

        if (!Lineage::isOneToOne($model, $relationName = NameParser::firstTerm($requestName))) {
            throw new NotOneToOneRelationException($requestName, $relationName);
        }

        if (!$model->{$relationName}) {
            $model->setRelation($relationName, $model->{$relationName}()->getRelated()->newInstance());
        }

        return [
            $model->{$relationName},
            NameParser::secondTerm($requestName),
            $relationName,
        ];
    }

    /**
     * Sets the extra attributes.
     *
     * @param Kompo\Elements\Field $field
     * @param array                  $extraAttributes
     */
    public static function setExtraAttributes($field, $extraAttributes)
    {
        static::setConfig($field, 'extraAttributes', array_replace(static::getConfig($field, 'extraAttributes'), $extraAttributes));
    }

    /**
     * Gets the eloquent configuration of the field.
     *
     * @param Kompo\Elements\Field $field     The field
     * @param string                 $configKey The desired configuration key.
     *
     * @return mixed
     */
    public static function getConfig($field, $configKey)
    {
        return $field->_kompo('eloquent')[$configKey];
    }

    /**
     * Sets the eloquent configuration of the field.
     *
     * @param Kompo\Elements\Field $field       The field
     * @param string                 $configKey   The desired configuration key.
     * @param mixed                  $configValue The desired configuration value.
     *
     * @return self
     */
    public static function setConfig($field, $configKey, $configValue)
    {
        return $field->_kompo('eloquent', [$configKey => $configValue]);
    }
}
