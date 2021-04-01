<?php

namespace Kompo\Interactions\Actions;

use Kompo\Core\KompoAction;
use Kompo\Core\KompoTarget;
use Kompo\Routing\RouteFinder;

trait AxiosRequestActions
{
    protected function prepareAxiosRequest($config)
    {
        return $this->prepareAction('axiosRequest', $config);
    }

    public function selfHttpRequest($requestType, $kompoAction, $classOrMethod, $ajaxPayload = null)
    {
        return $this->prepareAxiosRequest(array_merge(
            [
                'route'       => RouteFinder::getKompoRoute($requestType, $ajaxPayload),
                'routeMethod' => $requestType,
                'ajaxPayload' => $ajaxPayload,
            ],
            KompoAction::headerArray($kompoAction),
            KompoTarget::getEncryptedArray($classOrMethod)
        ));
    }

    /**
     * Includes additional komponents from the server. If they contain Fields, they will be included in the Form data and handled in an Eloquent Form.
     * To display the new Komponents, you need to chain a method `inModal` or `inPanel`. For example:
     * <php>->getKomponents('newKomponentsMethod')->inPanel('panel-id')</php>.
     *
     *
     * @param string     $methodName  The class's method name that will return the new komponents.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     * @param boolean|null $withAllFormValues Posts ALL the form fields values along with the payload.
     *
     * @return self
     */
    public function getKomponents($methodName, $ajaxPayload = null, $withAllFormValues = false)
    {
        $this->applyToElement(function ($el) use ($methodName) {
            $el->config(['includes' => $methodName]);
        });

        return $this->selfHttpRequest('POST', 'include-komponents', $methodName, $ajaxPayload)
            ->config(['included' => true]) //to tell Panel to include rather than separate form
            ->config(['withAllFormValues' => $withAllFormValues]);
    }

    /**
     * Loads a fresh Komposer class from the server.
     * To display the new Komposer, you need to chain a method `inModal` or `inPanel`.
     * You may also pass it additional data in the $payload argument. This data will be injected in the Komposer's store.
     * Note that the new Komposer will be separate from the parent Komposer where this call is made. If you wanted to include the Komponents as part of the parent, use `getKomponents()` instead.
     * For example:
     * <php>->getKomposer(PostForm::class, ['payload' => 'some-data'])->inModal()</php>.
     *
     * @param string     $methodName  The method name that will return a view or HTML response.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function getKomposer($komposerClass, $ajaxPayload = null)
    {
        //currently using same slot as method for kompoClass... why not?
        return $this->selfHttpRequest('POST', 'load-komposer', $komposerClass, $ajaxPayload);
    }

    /**
     * Loads a backend Blade view from the server.
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`.
     * You may also pass it additional data in the $payload argument. This data will be available with the request() helper.
     * For example:
     * <php>->loadView('route-of-view')->inModal()</php>.
     *
     * @param string     $viewPath    The view path as in the view() helper.
     * @param array|null $ajaxPayload Additional custom data to include in the view (optional).
     *
     * @return self
     */
    public function getView($viewPath, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('POST', 'get-view', $viewPath, $ajaxPayload);
    }

    /**
     * Loads a view with a GET ajax request.
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example:
     * <php>->getView('get-route-of-view')->inModal()</php>.
     *
     * @param string     $methodName  The class's method name that will return the new komponents.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function selfGet($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('GET', 'self-method', $methodName, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request.
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example:
     * <php>->postView('get-route-of-view')->inModal()</php>.
     *
     * @param string     $methodName  The class's method name that will return the new komponents.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function selfPost($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('POST', 'self-method', $methodName, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request.
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example:
     * <php>->postView('get-route-of-view')->inModal()</php>.
     *
     * @param string     $methodName  The class's method name that will return the new komponents.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function selfPut($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('PUT', 'self-method', $methodName, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request.
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example:
     * <php>->postView('get-route-of-view')->inModal()</php>.
     *
     * @param string     $methodName  The class's method name that will return the new komponents.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function selfDelete($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('DELETE', 'self-method', $methodName, $ajaxPayload);
    }

    //TODO: document
    public function setHistory($route, $parameters)
    {
        return $this->prepareAction('setHistory', [
            'setHistory' => RouteFinder::guessRoute($route, $parameters),
        ]);
    }
}
