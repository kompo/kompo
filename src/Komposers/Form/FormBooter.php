<?php

namespace Kompo\Komposers\Form;

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

        $form->setModel($form->model);

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

        $form->setModel($form->model);

        AuthorizationGuard::checkBoot($form, 'Display');

        FormDisplayer::displayKomponents($form);

        KomposerManager::booted($form);

        return $form;
    }
}
