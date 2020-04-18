<?php

namespace Kompo\Komposers\Form;

use Illuminate\Database\Eloquent\Model;
use Kompo\Core\AuthorizationGuard;
use Kompo\Form;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\RouteFinder;

class FormBooter
{
    public static function bootForAction($session)
    {
        $form = static::instantiateUnbooted($session['kompoClass']);

        $form->store($session['store']);
        $form->parameter($session['parameters']);
        $form->modelKey($session['modelKey']);
        $form->model($form->model);

        AuthorizationGuard::checkBoot($form);
        
        KomposerManager::prepareComponentsForAction($form, 'components'); //mainly to retrieve rules from fields

        return $form;
    }

	public static function bootForDisplay($form, $modelKey = null, $store = [])
	{
        $form = static::instantiateUnbooted($form);

        $form->store($store);
        $form->parameter(RouteFinder::getRouteParameters());
        $form->modelKey($modelKey);
        $form->model($form->model);
        
        AuthorizationGuard::checkBoot($form);

		return FormDisplayer::displayComponents($form);
	}

    /**
     * Initialize or find the model (if komposer linked to a model).
     *
     * @param Kompo\Komposer\Komposer $komposer
     * @param Illuminate\Database\Eloquent\Model|null $model
     * @return void
     */
    public static function setModel($komposer, $model = null)
    {
        if(is_null($model))
            return;

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
        return '<vl-form :vkompo="'.htmlspecialchars($form).'"></vl-form>';
    }


    /**
     * Returns an unbooted Form if called with it's class string.
     *
     * @param mixed $class  The class or object
     *
     * @return 
     */
    protected static function instantiateUnbooted($class)
    {
        return $class instanceOf Form ? $class : new $class(null, null, true);
    }

}