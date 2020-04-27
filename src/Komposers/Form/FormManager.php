<?php
namespace Kompo\Komposers\Form;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\DependencyResolver;

class FormManager extends FormBooter
{
    public static function handlePost($form)
    {
        AuthorizationGuard::checkIfAllowedToPost($form);

        DependencyResolver::callKomposerMethod($form);
    }

}