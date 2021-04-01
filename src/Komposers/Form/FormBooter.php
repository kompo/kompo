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

        $form->model($form->model);

        KompoId::setForKomposer($form, $bootInfo);

        AuthorizationGuard::checkBoot($form);

        ValidationManager::addRulesToKomposer($form->rules(), $form);

        KomposerManager::prepareKomponentsForAction($form, 'komponents', true); //mainly to retrieve rules from fields

        return $form;
    }

    public static function bootForDisplay($formClass, $modelKey = null, $store = [], $routeParams = null)
    {
        $form = $formClass instanceof Form ? $formClass : new $formClass($modelKey, $store);

        $form->parameter($routeParams ?: RouteFinder::getRouteParameters());

        $form->model($form->model);

        AuthorizationGuard::checkBoot($form);

        FormDisplayer::displayKomponents($form);

        KomposerManager::booted($form);

        return $form;
    }

    /**
     * Initialize or find the model (if komposer linked to a model).
     *
     * @param Kompo\Komposer\Komposer                 $komposer
     * @param Illuminate\Database\Eloquent\Model|null $model
     *
     * @return void
     */
    public static function setModel($komposer, $model = null)
    {
        if (is_null($model)) {
            return;
        }

        $komposer->model = $model instanceof Model ? $model : $model::findOrNew($komposer->modelKey());
        $komposer->modelKey($komposer->model()->getKey()); //set if it wasn't (ex: dynamic model set in created() phase)

        return $komposer->model;
    }

    /**
     * Shortcut method to render a Form into it's Vue component.
     *
     * @return string
     */
    public static function renderVueComponent($form)
    {
        return '<'.$form->vueKomposerTag.' :vkompo="'.htmlspecialchars($form).'"></'.$form->vueKomposerTag.'>';
    }
}
