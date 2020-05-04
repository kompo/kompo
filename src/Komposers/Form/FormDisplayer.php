<?php
namespace Kompo\Komposers\Form;

use Kompo\Core\KompoInfo;
use Kompo\Core\ValidationManager;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\RouteFinder;

class FormDisplayer extends FormBooter
{
    public static function displayKomponents($form)
    {
        static::prepareSubmitRedirectRoutes($form);

        ValidationManager::addRulesToKomposer($form->rules(), $form); //for Front-end validations TODO:

        $form->komponents = KomposerManager::prepareKomponentsForDisplay($form, 'komponents', true);

        KompoInfo::saveKomposer($form, ['modelKey' => $form->modelKey()]);

        return $form;
    }

    /**
     * Initialize the submit and redirect behavior.
     *
     * @return void
     */
    protected static function prepareSubmitRedirectRoutes($form)
    {
        $form->data([
            'emitFormData' => $form->emitFormData
        ]);

        $options = $form->_kompo('options');

        if($options['preventSubmit'])
            return;

        $form->data([
            'submitUrl' => $options['submitTo'] ? RouteFinder::matchRoute($options['submitTo']) :
                    ($form->submitUrl() ? : 
                    ((method_exists($form, 'handle') || $form->model) ? RouteFinder::getKompoRoute() : null)),
            'submitMethod' => $options['submitMethod']
        ]);

        if($form->data('submitUrl') == RouteFinder::getKompoRoute())
            $form->data([
                'submitAction' => method_exists($form, 'handle') ? 'handle-submit' : 'eloquent-submit'
            ]);

        if($options['redirectTo'])
            $form->data([
                'redirectUrl' => RouteFinder::matchRoute($options['redirectTo']),
                'redirectMessage' => __($options['redirectMessage'])
            ]);
    }

}