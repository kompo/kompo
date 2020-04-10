<?php

namespace Kompo\Komposers;

use Kompo\Core\AuthorizationGuard;
use Kompo\Exceptions\FormMethodNotFoundException;
use Kompo\Exceptions\NotFoundKompoActionException;
use Kompo\Komposers\Catalog\CatalogDisplayer;
use Kompo\Komposers\Form\FormDisplayer;
use Kompo\Komposers\Form\FormManager;
use Kompo\Komposers\Form\FormSubmitter;
use Kompo\Komposers\KomposerManager;
use Kompo\Select;

class KomposerHandler
{
    public static function performAction($komposer)
    {
        switch(request()->header('X-Kompo-Action'))
        {
            case 'eloquent-submit':
                return FormSubmitter::eloquentSave($komposer);

            case 'handle-submit':
                return FormSubmitter::callCustomHandle($komposer);

            case 'post-to-form':
                return FormManager::handlePost($komposer);

            case 'include-komponents':
                return KomposerManager::prepareComponentsForDisplay($komposer, request()->header('X-Kompo-Method'));

            case 'self-method':
                return null; //TODO

            case 'search-options':
                return static::getMatchedSelectOptions($komposer);

            case 'updated-option':
                return static::reloadUpdatedSelectOptions($komposer);

            case 'browse-items':
                return CatalogDisplayer::browseCards($komposer);

            case 'order':
                return CatalogManager::orderItems($komposer);

            case 'delete':
                return CatalogManager::deleteModel($komposer);
        }

        throw new NotFoundKompoActionException(get_class($komposer));
    }


    /**
     * Gets the matched select options for Catalogs or Forms.
     *
     * @param      <type>                       $komposer   The Komposer
     *
     * @throws     FormMethodNotFoundException  (description)
     *
     * @return     <type>                       The matched select options.
     */
    public static function getMatchedSelectOptions($komposer)
    {
        AuthorizationGuard::checkIfAllowedToSeachOptions($komposer);

        if(method_exists($komposer, $method = request('method'))){
            return Select::transformOptions($komposer->{$method}(request('search')));
        }else{
            throw new FormMethodNotFoundException($method);
        }
    }

    /**
     * { function_description }
     *
     * @param      <type>  $komposer   The Komposer
     *
     * @return     <type>
     */
    public static function reloadUpdatedSelectOptions($komposer)
    {
        foreach ($komposer->getFieldComponents() as $field) {
            if($field->name == request('selectName'))
                return $field->options;
        }
    }


}