<?php

namespace Kompo\Komponents\Form;

use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Core\ValidationManager;
use Kompo\Komponents\KomponentManager;
use Kompo\Routing\RouteFinder;

class FormDisplayer
{
    public static function displayElements($form)
    {
        static::prepareSubmitRedirectRoutes($form);

        ValidationManager::addRulesToKomponent($form->rules(), $form); //for Front-end validations TODO:

        $form->elements = $form->prepareOwnElementsForDisplay($form->render());

        KompoId::setForKomponent($form);

        KompoInfo::saveKomponent($form, ['modelKey' => $form->modelKey()]);

        return $form;
    }

    /**
     * Initialize the submit and redirect behavior.
     *
     * @return void
     */
    protected static function prepareSubmitRedirectRoutes($form)
    {
        $form->config([
            'emitFormData' => $form->emitFormData,
        ]);

        $options = $form->_kompo('options');

        if ($options['preventSubmit']) {
            return;
        }

        $form->config([
            'submitUrl' => $options['submitTo'] ? RouteFinder::matchRoute($options['submitTo']) :
                    ($form->submitUrl() ?:
                    ((method_exists($form, 'handle') || $form->model) ? RouteFinder::getKompoRoute() : null)),
            'submitMethod'         => $options['submitMethod'],
            'validationErrorAlert' => __($options['validationErrorAlert']),
        ]);

        if ($form->config('submitUrl') == RouteFinder::getKompoRoute()) {
            $form->config([
                'submitAction' => method_exists($form, 'handle') ? 'handle-submit' : 'eloquent-submit',
            ]);
        }

        if ($options['redirectTo']) {
            $form->config([
                'redirectUrl'     => RouteFinder::matchRoute($options['redirectTo']),
                'redirectMessage' => __($options['redirectMessage']),
            ]);
        }
    }
}
