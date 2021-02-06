<?php

namespace Kompo\Komposers\Form;

use Illuminate\Database\Eloquent\Model;
use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Core\ValidationManager;
use Kompo\Form;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\RouteFinder;

class FormBooter
{
    public static function bootForAction($bootInfo)
    {        
        $form = static::instantiateUnbooted($bootInfo['kompoClass']);

        KompoId::setForKomposer($form, $bootInfo);
        
        $modelKey = $bootInfo['modelKey'];
        $model = $form->model;

        if($modelKey instanceof Model){
            $model = $modelKey; //made this so we can boot a model (with predefined attributes) with MultiForm for ex. 
            $modelKey = $model->getKey();
        }

        $form->store($bootInfo['store']);
        $form->parameter($bootInfo['parameters']);
        $form->modelKey($modelKey);
        $form->model($model);

        AuthorizationGuard::checkBoot($form);
        
        ValidationManager::addRulesToKomposer($form->rules(), $form);
        
        KomposerManager::prepareKomponentsForAction($form, 'komponents', true); //mainly to retrieve rules from fields

        return $form;
    }

	public static function bootForDisplay($form, $modelKey = null, $store = [], $routeParams = null)
	{
        $form = static::instantiateUnbooted($form);

        if(is_array($modelKey)) //Allow permutation of arguments
        {
            $newStore = $modelKey;
            $modelKey = is_array($store) ? null : $store;
            $store = $newStore;
        }
        
        $model = $form->model;

        if($modelKey instanceof Model){
            $model = $modelKey; //made this so we can boot a model (with predefined attributes) with MultiForm for ex. 
            $modelKey = $model->getKey();
        }

        $form->store($store);
        $form->parameter($routeParams ?: RouteFinder::getRouteParameters());
        $form->modelKey($modelKey);
        $form->model($model);
        
        AuthorizationGuard::checkBoot($form);

        FormDisplayer::displayKomponents($form);

        KomposerManager::booted($form);

		return $form;
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
        return '<'.$form->vueKomposerTag.' :vkompo="'.htmlspecialchars($form).'"></'.$form->vueKomposerTag.'>';
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