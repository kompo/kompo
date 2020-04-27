<?php
namespace Kompo\Komposers\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\DependencyResolver;
use Kompo\Core\Util;
use Kompo\Core\ValidationManager;
use Kompo\Database\ModelManager;
use Kompo\Komponents\FormField;
use Kompo\Komposers\Form\FormDisplayer;
use Kompo\Komposers\KomposerManager;

class FormSubmitter extends FormBooter
{
    protected static function prepareForSubmit($form)
    {
        ValidationManager::addRulesToKomposer($form->rules(), $form);

        AuthorizationGuard::mainGate($form);

        ValidationManager::validateRequest($form);
    } 

    public static function callCustomHandle($form)
    {
        static::prepareForSubmit($form);

        return DependencyResolver::callKomposerMethod($form, null, [], 'handle');
    }

    public static function eloquentSave($form)
    {
        KomposerManager::prepareKomponentsForAction($form, 'komponents'); //mainly to retrieve rules from fields

        static::prepareForSubmit($form);

        return static::saveModel($form);
    }

    /**
     * Save an Eloquent model.
     * 
     * @param  Kompo\Form  $form
     *
     * @return void
     */
    public static function saveModel($form)
    {
        static::loopOverFieldsFor('fillBeforeSave', $form);

        static::beforeSaveHook($form);

        ModelManager::saveAttributes($form->model);

        $form->modelKey($form->model->getKey());

        static::afterSaveHook($form);

        static::loopOverFieldsFor('fillAfterSave', $form);

        static::loopOverFieldsFor('fillHasMorphOne', $form);

        static::completedHook($form);

        return static::returnResponseHook($form);
    }


    /**
     * A method that gets executed before the model has been saved.
     * 
     * @param  Kompo\Form  $form
     * 
     * @return void
     */
    protected static function beforeSaveHook($form)
    {
        if(method_exists($form, 'beforeSave'))
            $form->beforeSave();
    }

    /**
     * A method that gets executed after the model has been saved (before relationships).
     * 
     * @param  Kompo\Form  $form
     * 
     * @return void
     */
    protected static function afterSaveHook($form)
    {
        if(method_exists($form, 'afterSave'))
            $form->afterSave();
    }

    /**
     * A method that gets executed at the end of the lifecycle (after relationships have been saved).
     * 
     * @param  Kompo\Form  $form
     * 
     * @return void
     */
    protected static function completedHook($form)
    {
        if(method_exists($form, 'completed'))
            $form->completed();
    }

    /**
     * Sets a specific return response for the form.
     *
     * @param  Kompo\Form  $form
     * 
     * @return Response
     */
    protected static function returnResponseHook($form)
    {
        if(method_exists($form, 'response'))
            return $form->response();

        if($form->_kompo('options', 'refresh'))
            return response()->json(['form' => 
                FormDisplayer::displayKomponents($form)
            ], 202);

        if(config('kompo.eloquent_form.return_model_as_response'))
            return $form->model;

        return $form;
    }


    protected static function loopOverFieldsFor($stage, $komposer)
    {
        $hasMorphOneModels = [];

        foreach (KomposerManager::collectFields($komposer) as $fieldKey => $field) {

            $processed = FormField::fillDuringStage($stage, $field, $komposer);

            if($processed && $processed->count()){
                
                KomposerManager::removeField($komposer, $fieldKey);
                
                if($stage == 'fillHasMorphOne')
                    $hasMorphOneModels = array_unique(array_merge($hasMorphOneModels, $processed->toArray()));
            }
        }

        foreach ($hasMorphOneModels as $nestedModel) {
            $komposer->model->{$nestedModel}()->save($komposer->model->{$nestedModel});
            $komposer->model->load($nestedModel);
        }
    }
}