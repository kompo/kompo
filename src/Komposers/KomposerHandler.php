<?php

namespace Kompo\Komposers;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\DependencyResolver;
use Kompo\Core\KompoAction;
use Kompo\Core\KompoTarget;
use Kompo\Exceptions\NotFoundKompoActionException;
use Kompo\Komposers\Form\FormDisplayer;
use Kompo\Komposers\Form\FormManager;
use Kompo\Komposers\Form\FormSubmitter;
use Kompo\Komposers\KomposerManager;
use Kompo\Komposers\Query\QueryDisplayer;
use Kompo\Komposers\Query\QueryManager;
use Kompo\Routing\Dispatcher;
use Kompo\Select;

class KomposerHandler
{
    public static function performAction($komposer)
    {
        switch(KompoAction::header())
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

            case 'get-view':
                return static::returnBladeView($komposer);

            case 'search-options':
                return static::getMatchedSelectOptions($komposer);

            case 'browse-items':
                return QueryDisplayer::browseCards($komposer);

            case 'order-items':
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
        AuthorizationGuard::mainGate($komposer, 'selfAjax');

        return DependencyResolver::callKomposerMethod($komposer, KompoTarget::getDecrypted(), request()->all());
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
        AuthorizationGuard::mainGate($komposer, 'search-options');
        
        return Select::transformOptions(
            DependencyResolver::callKomposerMethod($komposer, KompoTarget::getDecrypted(), [
                'search' => request('search')
            ])
        );
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
        return with(new Dispatcher($komposerClass))->bootKomposerForDisplay();
    }

    /**
     * Returns a Blade view.
     *
     * @param Kompo\Komposers\Komposer $komposer  The parent komposer
     *
     * @return Kompo\Komposers\Komposer
     */
    protected static function returnBladeView($komposer)
    {
        $viewPath = KompoTarget::getDecrypted();
        return view($viewPath, request()->all());
    }

    /**
     * Deletes a database record
     * 
     * @param  string|integer $id [Object's key]
     * @return \Illuminate\Http\Response     [redirects back to current page]
     */
    public static function deleteRecord($komposer)
    {
        $model = KompoTarget::getDecrypted();

        //$record = $komposer->model->newInstance()->findOrFail($deleteKey);
        $record = $model::findOrFail(request('deleteKey'));

        if( 
            (method_exists($record, 'deletable') && $record->deletable()) 
            || 
            (defined(get_class($record).'::DELETABLE_BY') && $record::DELETABLE_BY &&
                optional(auth()->user())->hasRole($record::DELETABLE_BY))
            
            || optional(auth()->user())->can('delete', $record)
        ){
            $record->delete();
            return 'deleted!';
        }

        return abort(403, __('Sorry, you are not authorized to delete this item.'));
    }

}