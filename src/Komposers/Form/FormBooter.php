<?php

namespace Kompo\Komposers\Form;

use Kompo\Form;
use Kompo\Core\Util;
use Kompo\Routing\RouteFinder;
use Illuminate\Database\Eloquent\Model;

class FormBooter extends Form
{
	public function __construct()
	{
        //overriden
	}

    public static function performAction($session)
    {
        $form = static::instantiateUnbooted($session['kompoClass']);

        $form->store($session['store']);
        $form->parameter($session['parameters']);
        $form->modelKey($session['modelKey']);
        $form->model($form->model);

        switch(request()->header('X-Kompo-Action'))
        {
            case 'eloquent-submit':
                return FormSubmitter::eloquentSave($form);

            case 'post-to-form':
                return FormManager::handlePost($form);

            case 'include-fields':
                return FormDisplayer::includeFields($form);

            case 'handle-submit':
                return FormSubmitter::callCustomHandle($form);

            case 'search-options':
                return FormManager::getMatchedSelectOptions($form);

            case 'updated-option':
                return FormManager::reloadUpdatedSelectOptions($form);
        }

    }

	public static function bootForDisplay($form, $modelKey = null, $store = [])
	{
        $form = static::instantiateUnbooted($form);

        $form->store($store);
        $form->parameter(RouteFinder::getRouteParameters());
        $form->modelKey($modelKey);
        $form->model($form->model);

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
        return '<vl-form :vcomponent="'.htmlspecialchars($form).'"></vl-form>';
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