<?php

namespace Kompo\Komposers\Form;

use Kompo\Form;
use Kompo\Routing\RouteFinder;
use Kompo\Utilities\Arr;

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

            case 'include-fields':
                return FormDisplayer::includeFields($form);

            case 'handle-submit':
                return FormSubmitter::callCustomHandle($form);

            case 'search-options':
                return FormCaller::getMatchedSelectOptions($form);

            case 'updated-option':
                return FormCaller::reloadUpdatedSelectOptions($form);
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

    protected static function collectFrom($komposer, $includes)
    {
        if($includes && !method_exists($komposer, $includes))
            throw new IncludesMethodNotFoundException($includes);

        return Arr::collect($includes ? $komposer->{$includes}() : $komposer->components());
    }

}