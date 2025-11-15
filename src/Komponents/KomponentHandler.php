<?php

namespace Kompo\Komponents;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\DependencyResolver;
use Kompo\Core\KompoAction;
use Kompo\Core\KompoTarget;
use Kompo\Exceptions\NotFoundKompoActionException;
use Kompo\Komponents\Form\FormSubmitter;
use Kompo\Komponents\Query\QueryDisplayer;
use Kompo\Komponents\Query\QueryManager;
use Kompo\Routing\Dispatcher;
use Kompo\Routing\RouteFinder;
use Kompo\Select;

class KomponentHandler
{
    public static function performAction($komponent)
    {
        app()->instance('bootFlag', true);

        switch (KompoAction::header()) {
            case 'eloquent-submit':
                return $komponent->eloquentSave();
                //return FormSubmitter::eloquentSave($komponent);

            case 'handle-submit':
                return $komponent->callCustomHandle();
                //return FormSubmitter::callCustomHandle($komponent);

            case 'include-elements':
                return static::getIncludedElements($komponent);

            case 'self-method':
                return static::selfAjaxMethod($komponent);

            case 'load-komponent':
                return static::getKomponentFromElement($komponent);

            case 'get-view':
                return static::returnBladeView($komponent);

            case 'search-options':
                return static::getMatchedSelectOptions($komponent);

            case 'browse-items':
                return QueryDisplayer::browseCards($komponent);

            case 'order-items':
                return QueryManager::orderItems($komponent);

            case 'delete-item':
                return static::deleteRecord($komponent);
        }

        throw new NotFoundKompoActionException(get_class($komponent));
    }

    /**
     * Gets the matched select options for Querys or Forms.
     *
     * @param Kompo\Komponents\Komponent $komponent The parent komponent
     *
     * @throws KomponentMethodNotFoundException (description)
     *
     * @return <type> The matched select options.
     */
    public static function selfAjaxMethod($komponent)
    {
        AuthorizationGuard::mainGate($komponent, 'selfAjax');

        return DependencyResolver::callKomponentMethod($komponent, KompoTarget::getDecrypted(), request()->all());
    }

    /**
     * Gets the matched select options for Querys or Forms.
     *
     * @param Kompo\Komponents\Komponent $komponent The parent komponent
     *
     * @throws KomponentMethodNotFoundException (description)
     *
     * @return <type> The matched select options.
     */
    public static function getMatchedSelectOptions($komponent)
    {
        AuthorizationGuard::mainGate($komponent, 'search-options');

        return Select::transformOptions(
            DependencyResolver::callKomponentMethod($komponent, KompoTarget::getDecrypted(), [
                'search' => request('search'),
            ])
        );
    }

    /**
     * Gets the elements from the back-end to be included in the parent Komponent.
     *
     * @param <type> $komponent The komponent
     */
    protected static function getIncludedElements($komponent)
    {
        return KomponentManager::prepareElementsForDisplay($komponent);
    }

    /**
     * Gets the form or query class from the element and returns it booted.
     *
     * @param Kompo\Komponents\Komponent $komponent The parent komponent
     *
     * @return Kompo\Komponents\Komponent
     */
    protected static function getKomponentFromElement($komponent)
    {
        $komponentClass = KompoTarget::getDecrypted();

        if (config('kompo.komponent_route_strict')) {

            $routeObject = RouteFinder::findRouteByKomponent($komponentClass);
            
            return $routeObject ? \Http::get(url($routeObject->uri)) : _Rows(
                _Html('Sorry, this komponent class cannot be called directly'),
                _Html('Please set a route that points to it to allow this action.'),
                _Link('See fore more info (TODO add link to doc)'),
            )->style('padding:2rem');

        } else {

            return with(new Dispatcher($komponentClass))->bootKomponentForDisplay();
            
        }
    }

    /**
     * Returns a Blade view.
     *
     * @param Kompo\Komponents\Komponent $komponent The parent komponent
     *
     * @return Kompo\Komponents\Komponent
     */
    protected static function returnBladeView($komponent)
    {
        $viewPath = KompoTarget::getDecrypted();

        return view($viewPath, request()->all());
    }

    /**
     * Deletes a database record.
     *
     * @param string|int $id [Object's key]
     *
     * @return \Illuminate\Http\Response [redirects back to current page]
     */
    public static function deleteRecord()
    {
        $model = KompoTarget::getDecrypted();

        $record = $model::findOrFail(request('deleteKey'));

        if (
            (method_exists($record, 'deletable') && $record->deletable())
            ||
            (defined(get_class($record).'::DELETABLE_BY') && $record::DELETABLE_BY &&
                optional(auth()->user())->hasRole($record::DELETABLE_BY))

            || optional(auth()->user())->can('delete', $record)
        ) {
            $record->delete();

            return 'deleted!';
        }

        return abort(403, __('Sorry, you are not authorized to delete this item.'));
    }
}
