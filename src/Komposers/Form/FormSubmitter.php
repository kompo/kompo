<?php
namespace Kompo\Komposers\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\ValidationManager;
use Kompo\Eloquent\ModelManager;
use Kompo\Exceptions\FormMethodNotFoundException;
use Kompo\Komposers\KomposerManager;

class FormSubmitter extends FormBooter
{ 
    public static function callCustomHandle($form)
    {
        if(($method = request()->header('X-Kompo-Handle')) && !method_exists($form, $method))
            throw new FormMethodNotFoundException($method);

        $method = $method ?: 'handle';

        AuthorizationGuard::checkBoot($form);

        KomposerManager::created($form);

        static::prepareComponentsForSave($form);

        ValidationManager::addRulesToKomposer($form->rules(), $form);

        AuthorizationGuard::mainGate($form);

        return $form->{$method}(request());

    }

    public static function eloquentSave($form)
    {
        AuthorizationGuard::checkBoot($form);

        KomposerManager::created($form);

        static::prepareComponentsForSave($form);

        ValidationManager::addRulesToKomposer($form->rules(), $form);

        AuthorizationGuard::mainGate($form);

        return static::saveModel($form, request()); //TODO remove  request()
    }

    /**
     * Prepare the Form's components.
     *
     * @return void
     */
    protected static function prepareComponentsForSave($komposer, $includes = null)
    {
        static::collectFrom($komposer, $includes)->filter()->each( function($component) use ($komposer) {

            $component->prepareForSave($komposer);

            $component->mountedHook($komposer);

        }); //components are pushed in fields
    }

    /**
     * Save an Eloquent model.
     *
     * @return void
     */
    protected static function saveModel($komposer, $request)
    {
        collect($komposer->components)->each( function($field) use ($request, $komposer) {

            $field->fillBeforeSave($request, $komposer->model);

        });

        static::beforeSaveHook($komposer);

        ModelManager::saveAttributes($komposer->model);

        $komposer->modelKey($komposer->model->getKey());

        static::afterSaveHook($komposer);


        collect($komposer->components)->each( function($field) use ($request, $komposer) {

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