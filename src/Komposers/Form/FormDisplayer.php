<?php
namespace Kompo\Komposers\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\SessionStore;
use Kompo\Core\ValidationManager;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\Router;

class FormDisplayer extends FormBooter
{
    public static function displayComponents($form)
    {
        AuthorizationGuard::checkBoot($form);

        KomposerManager::created($form);

        static::prepareSubmitRedirectRoutes($form);

        ValidationManager::addRulesToKomposer($form->rules(), $form); //for Front-end validations TODO:

        static::prepareComponentsForDisplay($form);

        SessionStore::saveKomposer($form, ['modelKey' => $form->modelKey()]); 

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

        if($form->preventSubmit)
            return;

        $form->data([
            'submitUrl' => $form->submitTo ? Router::matchRoute($form->submitTo) :
                ($form->submitUrl() ? : 
                    ((method_exists($form, 'handle') || $form->model) ? route('_kompo') : null)),
            'submitMethod' => $form->submitMethod
        ]);

        $form->data([
            'redirectUrl' => $form->redirectTo ? Router::matchRoute($form->redirectTo) : null,
            'redirectMessage' => __($form->redirectMessage)
        ]);
    }

    /**
     * Prepare the Form's components.
     *
     * @return void
     */
    public static function prepareComponentsForDisplay($komposer)
    {
        $komposer->components = static::collectFrom($komposer, request()->header('X-Kompo-Includes'))->filter()->each( function($component) use ($komposer) {

            $component->prepareForDisplay($komposer);

            $component->mountedHook($komposer);

        })->values()->all();
    }

}