<?php
namespace Kompo\Komposers\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\DependencyResolver;
use Kompo\Exceptions\FormMethodNotFoundException;
use Kompo\Komposers\KomposerManager;
use Kompo\Select;
use ReflectionMethod;

class FormManager extends FormBooter
{
    public static function handlePost($form)
    {
        AuthorizationGuard::checkBoot($form);

        KomposerManager::created($form);

        AuthorizationGuard::checkIfAllowedToPost($form);

        if(method_exists($form, $method = request()->header('X-Kompo-Method'))){

            return $form->{$method}(...DependencyResolver::resolveMethodDependencies([/* no route params*/], new ReflectionMethod($form, $method)));
        }else{
            throw new FormMethodNotFoundException($method);
        }
    }

    public static function getMatchedSelectOptions($form)
    {
        AuthorizationGuard::checkBoot($form);

        KomposerManager::created($form);

        AuthorizationGuard::checkIfAllowedToSeachOptions($form);

        if(method_exists($form, $method = request('method'))){
            return Select::transformOptions($form->{$method}(request('search')));
        }else{
            throw new FormMethodNotFoundException($method);
        }
    }

    public static function reloadUpdatedSelectOptions($form)
    {
        foreach ($form->getFieldComponents() as $field) {
            if($field->name == request('selectName'))
                return $field->options;
        }
    }

}