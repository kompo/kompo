<?php

namespace Kompo\Komposers;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\DependencyResolver;
use Kompo\Core\KompoTarget;
use Kompo\Exceptions\NotFoundKompoActionException;
use Kompo\Komposers\Form\FormDisplayer;
use Kompo\Komposers\Form\FormManager;
use Kompo\Komposers\Form\FormSubmitter;
use Kompo\Komposers\KomposerManager;
use Kompo\Komposers\Query\QueryDisplayer;
use Kompo\Routing\Dispatcher;
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

            case 'include-komponents':
                return static::getIncludedKomponents($komposer);

            case 'self-method':
                return static::selfAjaxMethod($komposer);

            case 'load-komposer':
                return static::getKomposerFromKomponent($komposer);

            case 'search-options':
                return static::getMatchedSelectOptions($komposer);

            case 'updated-option':
                return static::reloadUpdatedSelectOptions($komposer);

            case 'browse-items':
                return QueryDisplayer::browseCards($komposer);

            case 'order':
                return QueryManager::orderItems($komposer);

            case 'delete-item':
                return static::deleteRecord($komposer);
        }

        throw new NotFoundKompoActionException(get_class($komposer));
    }


    /**
     * Gets the matched select options for Querys or Forms.
     *
     * @param Kompo\Komposers\Komposer $komposer  The parent komposer
     *
     * @throws     KomposerMethodNotFoundException  (description)
     *
     * @return     <type>                       The matched select options.
     */
    public static function selfAjaxMethod($komposer)
    {
        AuthorizationGuard::mainGate($komposer);

        return DependencyResolver::callKomposerMethod($komposer, null, request()->all());
    }


    /**
     * Gets the matched select options for Querys or Forms.
     *
     * @param Kompo\Komposers\Komposer $komposer  The parent komposer
     *
     * @throws     KomposerMethodNotFoundException  (description)
     *
     * @return     <type>                       The matched select options.
     */
    public static function getMatchedSelectOptions($komposer)
    {
        AuthorizationGuard::checkIfAllowedToSeachOptions($komposer);

        return Select::transformOptions(
            DependencyResolver::callKomposerMethod($komposer, null, [
                'search' => request('search')
            ])
        );
    }

    /**
     * { function_description }
     *
     * @param Kompo\Komposers\Komposer $komposer  The parent komposer
     *
     * @return     <type>
     */
    public static function reloadUpdatedSelectOptions($komposer)
    {
        foreach (KomposerManager::collectFields($komposer) as $field) {

            if($field->name == request()->header('X-Komponent')){

                return $field->options;

            }
        }
    }

    /**
     * Gets the komponents from the back-end to be included in the parent Komposer.
     *
     * @param      <type>  $komposer  The komposer
     */
    protected static function getIncludedKomponents($komposer)
    {
        return KomposerManager::prepareKomponentsForDisplay($komposer);
    }

    /**
     * Gets the form or query class from komponent and returns it booted.
     *
     * @param Kompo\Komposers\Komposer $komposer  The parent komposer
     *
     * @return Kompo\Komposers\Komposer
     */
    protected static function getKomposerFromKomponent($komposer)
    {
        $komposerClass = KompoTarget::getDecrypted();
        return with(new Dispatcher($komposerClass))->bootFromRoute();
    }

    /**
     * Deletes a database record
     * 
     * @param  string|integer $id [Object's key]
     * @return \Illuminate\Http\Response     [redirects back to current page]
     */
    public static function deleteRecord($komposer)
    {
        $deleteKey = request('deleteKey');

        $record = $komposer->model->newInstance()->findOrFail($deleteKey);

        if( 
            (method_exists($record, 'deletable') && $record->deletable()) 
            || 
            (defined(get_class($record).'::DELETABLE_BY') && $record::DELETABLE_BY &&
                optional(auth()->user())->hasRole($record::DELETABLE_BY))
            
            /* Controversial...
            || optional(auth()->user())->hasRole('super-admin')*/
        ){
            $record->delete();
            return 'deleted!';
        }

        return abort(403, __('Sorry, you are not authorized to delete this item.'));
    }

}