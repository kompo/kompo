<?php
namespace Kompo\Komposers\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\ValidationManager;
use Kompo\Database\ModelManager;
use Kompo\Exceptions\FormMethodNotFoundException;
use Kompo\Komposers\KomposerManager;

class FormSubmitter extends FormBooter
{
    protected static function prepareForSubmit($form)
    {
        AuthorizationGuard::checkBoot($form);

        AuthorizationGuard::checkPreventSubmit($form);

        KomposerManager::created($form);

        KomposerManager::prepareComponentsForAction($form, 'components'); //mainly to retrieve rules from fields

        ValidationManager::addRulesToKomposer($form->rules(), $form);

        AuthorizationGuard::mainGate($form);
    } 

    public static function callCustomHandle($form)
    {
        if(($method = request()->header('X-Kompo-Handle') ?: 'handle') && !method_exists($form, $method))
            throw new FormMethodNotFoundException($method);

        static::prepareForSubmit($form);

        return $form->{$method}(request());
    }

    public static function eloquentSave($form)
    {
        static::prepareForSubmit($form);

        return static::saveModel($form, request()); //TODO remove  request()
    }

    /**
     * Save an Eloquent model.
     *
     * @return void
     */
    protected static function saveModel($komposer, $request)
    {
        KomposerManager::collectFields($komposer)->each( function($field) use ($request, $komposer) {

            $field->fillBeforeSave($request, $komposer->model);

        });

        static::beforeSaveHook($komposer);

        ModelManager::saveAttributes($komposer->model);

        $komposer->modelKey($komposer->model->getKey());

        static::afterSaveHook($komposer);


        KomposerManager::collectFields($komposer)->each( function($field) use ($request, $komposer) {

            $field->fillAfterSave($request, $komposer->model);

        });

        static::completedHook($komposer);

        return static::returnResponseHook($komposer);
    }


    /**
     * A method that gets executed before the model has been saved.
     * 
     * @return void
     */
    protected static function beforeSaveHook($komposer)
    {
        if(method_exists($komposer, 'beforeSave'))
            $komposer->beforeSave();
    }

    /**
     * A method that gets executed after the model has been saved (before relationships).
     * 
     * @return void
     */
    protected static function afterSaveHook($komposer)
    {
        if(method_exists($komposer, 'afterSave'))
            $komposer->afterSave();
    }

    /**
     * A method that gets executed at the end of the lifecycle (after relationships have been saved).
     * 
     * @return void
     */
    protected static function completedHook($komposer)
    {
        if(method_exists($komposer, 'completed'))
            $komposer->completed();
    }

    /**
     * Sets a specific return response for the form.
     *
     * @param  mixed  $model
     * @return Response
     */
    protected static function returnResponseHook($komposer)
    {
        if(method_exists($komposer, 'response'))
            return $komposer->response();

        return $komposer->model;
    }

}