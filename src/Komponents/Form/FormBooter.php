<?php

namespace Kompo\Komponents\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\ValidationManager;
use Kompo\Form;
use Kompo\Komponents\KomponentManager;
use Kompo\Routing\RouteFinder;

class FormBooter
{
    public static function bootForAction($bootInfo)
    {
        $formClass = $bootInfo['kompoClass'];

        $form = new $formClass($bootInfo['modelKey'], $bootInfo['store']);

        $form->parameter($bootInfo['parameters']);

        $form->setModel($form->model);

        KompoId::setForKomponent($form, $bootInfo);

        AuthorizationGuard::checkBoot($form, 'Action');

        ValidationManager::addRulesToKomponent($form->rules(), $form);

        KomponentManager::prepareElementsForAction($form, 'render', true); //mainly to retrieve rules from fields

        return $form;
    }

    public static function bootForDisplay($formClass, $modelKey = null, $store = [], $routeParams = null)
    {
        $form = $formClass instanceof Form ? $formClass : new $formClass($modelKey, $store);

        $form->parameter($routeParams ?: RouteFinder::getRouteParameters());

        $form->setModel($form->model);

        AuthorizationGuard::checkBoot($form, 'Display');

        FormDisplayer::displayElements($form);

        KomponentManager::booted($form);

        return $form;
    }
}
