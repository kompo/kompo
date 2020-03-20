<?php
namespace Kompo\Komposers\Form;

use Kompo\Exceptions\FormMethodNotFoundException;

class FormManager extends FormBooter
{
    public static function getMatchedSelectOptions($form)
    {
        if(method_exists($form, $method)){
            return Select::transformOptions($form->{$method}(request('search')));
        }else{
            throw new FormMethodNotFoundException($method);
        }
    }

    public static function reloadUpdatedSelectOptions($form)
    {
        foreach ($form->getFieldComponents() as $field) {
            if($field->name == request()->input('selectName'))
                return $field->options;
        }
    }

}