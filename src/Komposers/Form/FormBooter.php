<?php

namespace Kompo\Komposers\Form;

use Illuminate\Database\Eloquent\Model;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\ValidationManager;
use Kompo\Form;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\RouteFinder;

class FormBooter
{
    public static function bootForAction($bootInfo)
    {
        $formClass = $bootInfo['kompoClass'];

        $form = new $formClass($bootInfo['modelKey'], $bootInfo['store']);

        $form->parameter($bootInfo['parameters']);

        static::setModel($form, $form->model);

        KompoId::setForKomposer($form, $bootInfo);

        AuthorizationGuard::checkBoot($form, 'Action');

        ValidationManager::addRulesToKomposer($form->rules(), $form);

        KomposerManager::prepareKomponentsForAction($form, 'komponents', true); //mainly to retrieve rules from fields

        return $form;
    }

    public static function bootForDisplay($formClass, $modelKey = null, $store = [], $routeParams = null)
    {
        $form = $formClass instanceof Form ? $formClass : new $formClass($modelKey, $store);

        $form->parameter($routeParams ?: RouteFinder::getRouteParameters());

        static::setModel($form, $form->model);

        AuthorizationGuard::checkBoot($form, 'Display');

        FormDisplayer::displayKomponents($form);

        KomposerManager::booted($form);

        return $form;
    }

    /**
     * Initialize or find the model (if form linked to a model).
     *
     * @param Kompo\Form                              $form
     * @param Illuminate\Database\Eloquent\Model|null $model
     *
     * @return void
     */
    public static function setModel($form, $model = null)
    {
        if (is_null($model)) {
            return;
        }

        $form->model = $model instanceof Model ? $model : (
            $form->modelKey() ? $model::findOrNew($form->modelKey()) : new $model
        );
        $form->modelKey($form->model()->getKey()); //set if it wasn't (ex: dynamic model set in created() phase)

        $form->modelExists = $form->model->exists;

        return $form->model;
    }
}
