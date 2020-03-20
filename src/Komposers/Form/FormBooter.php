<?php

namespace Kompo\Komposers\Form;

use Kompo\Form;
use Kompo\Routing\Router;
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

            case 'search-select':
                return FormCaller::getMatchedSelectOptions($form);

            case 'updated-select':
                return FormCaller::reloadUpdatedSelectOptions($form);
        }

    }

	public static function bootForDisplay($form, $modelKey = null, $store = [])
	{
        $form = static::instantiateUnbooted($form);

        $form->store($store);
        $form->parameter(Router::getRouteParameters());
        $form->modelKey($modelKey);
        $form->model($form->model);

		return FormDisplayer::displayComponents($form);
	}

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