<?php

namespace Kompo\Komponents\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\DependencyResolver;
use Kompo\Core\KompoTarget;
use Kompo\Core\ValidationManager;
use Kompo\Database\ModelManager;
use Kompo\Elements\Managers\FormField;
use Kompo\Komponents\KomponentManager;

class FormSubmitter
{
    protected static function prepareForSubmit($form)
    {
        AuthorizationGuard::mainGate($form, 'submit');

        ValidationManager::validateRequest($form);
    }

    public static function callCustomHandle($form)
    {
        static::prepareForSubmit($form);

        $methodName = KompoTarget::getDecrypted();

        return DependencyResolver::callKomponentMethod($form, $methodName ?: 'handle', [], !$methodName);
    }

    public static function eloquentSave($form)
    {
        static::prepareForSubmit($form);

        return static::saveModel($form);
    }

    /**
     * Save an Eloquent model.
     *
     * @param Kompo\Form $form
     *
     * @return void
     */
    public static function saveModel($form)
    {        
        static::beforeFillHook($form);

        static::loopOverFieldsFor('fillBeforeSave', $form);

        static::beforeSaveHook($form);

        ModelManager::saveAttributes($form->model);

        $form->modelKey($form->model->getKey());

        static::afterSaveHook($form);

        static::loopOverFieldsFor('fillAfterSave', $form);

        static::loopOverFieldsFor('fillOneToOneBeforeSave', $form);

        static::loopOverFieldsFor('fillOneToOneAfterSave', $form);

        static::completedHook($form);

        return static::returnResponseHook($form);
    }

    /**
     * A method that gets executed before the model is automatically filled from attribute fields.
     *
     * @param Kompo\Form $form
     *
     * @return void
     */
    protected static function beforeFillHook($form)
    {
        if (method_exists($form, 'beforeFill')) {
            $form->beforeFill();
        }
    }

    /**
     * A method that gets executed before the model has been saved.
     *
     * @param Kompo\Form $form
     *
     * @return void
     */
    protected static function beforeSaveHook($form)
    {
        if (method_exists($form, 'beforeSave')) {
            $form->beforeSave();
        }
    }

    /**
     * A method that gets executed after the model has been saved (before relationships).
     *
     * @param Kompo\Form $form
     *
     * @return void
     */
    protected static function afterSaveHook($form)
    {
        if (method_exists($form, 'afterSave')) {
            $form->afterSave();
        }
    }

    /**
     * A method that gets executed at the end of the lifecycle (after relationships have been saved).
     *
     * @param Kompo\Form $form
     *
     * @return void
     */
    protected static function completedHook($form)
    {
        if (method_exists($form, 'completed')) {
            $form->completed();
        }
    }

    /**
     * Sets a specific return response for the form.
     *
     * @param Kompo\Form $form
     *
     * @return Response
     */
    protected static function returnResponseHook($form)
    {
        if (method_exists($form, 'response')) {
            return $form->response();
        }

        if ($form->_kompo('options', 'refresh') || $form->refreshAfterSubmit) {
            return response()->json(FormDisplayer::displayElements($form), 202);
        }

        if (config('kompo.eloquent_form.return_model_as_response')) {
            return $form->model;
        }

        return $form;
    }

    protected static function loopOverFieldsFor($stage, $komponent)
    {
        $oneToOneRelations = [];

        foreach (KomponentManager::collectFields($komponent) as $fieldKey => $field) {
            $processed = FormField::fillDuringStage($stage, $field, $komponent);

            if ($processed && $processed->count()) {
                KomponentManager::removeField($komponent, $fieldKey);

                if ($stage == 'fillOneToOneBeforeSave') { //only here do we need this, relations are auto-saved
                    //$oneToOneRelations = array_unique(array_merge($oneToOneRelations, $processed->toArray()));
                    foreach ($processed as $specs) {
                        $oneToOneRelations[] = $specs;
                    }
                }
            }
        }

        if (!count($oneToOneRelations)) {
            return;
        }

        $oneToOneRelations = collect($oneToOneRelations)->groupBy('relation');

        foreach ($oneToOneRelations as $relation => $names) {
            ModelManager::saveOneToOneOrDelete(
                $komponent->model, 
                $relation, 
                $names->pluck('name'),
                property_exists($komponent, 'deleteOneToOneIfEmpty') && $komponent->deleteOneToOneIfEmpty,
            );
        }
    }
}
