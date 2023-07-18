<?php

namespace Kompo\Interactions\Actions;

use Kompo\Core\KompoAction;
use Kompo\Core\KompoTarget;
use Kompo\Routing\RouteFinder;

trait AxiosRequestActions
{
    protected function prepareAxiosRequest($config)
    {
        $this->applyToElement(function ($el) {
            $el->class('cursor-pointer');
        });

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
     * Includes additional elements from the server. If they contain Fields, they will be included in the Form data and handled in an Eloquent Form.
     * To display the new Elements, you need to chain a method `inModal` or `inPanel`. For example:
     * <php>->getElements('newElementsMethod')->inPanel('panel-id')</php>.
     *
     *
     * @param string     $methodName        The class's method name that will return the new elements.
     * @param array|null $ajaxPayload       Additional custom data to add to the request (optional).
     * @param bool|null  $withAllFormValues Posts ALL the form fields values along with the payload.
     *
     * @return self
     */
    public function getElements($methodName, $ajaxPayload = null, $withAllFormValues = false)
    {
        $this->applyToElement(function ($el) use ($methodName) {
            $el->config(['includes' => $methodName]);
        });

        return $this->selfHttpRequest('POST', 'include-elements', $methodName, $ajaxPayload)
            ->config(['included' => true]) //to tell Panel to include rather than separate form
            ->config(['withAllFormValues' => $withAllFormValues]);
    }

    //A temporary alias for the above method to avoid breaking changes for the developpers, will be deprecated in v4.
    public function getKomponents($methodName, $ajaxPayload = null, $withAllFormValues = false)
    {
        return $this->getElements($methodName, $ajaxPayload, $withAllFormValues);
    }

    /**
     * Loads a fresh Komponent class from the server.
     * To display the new Komponent, you need to chain a method `inModal` or `inPanel`.
     * You may also pass it additional data in the $payload argument. This data will be injected in the Komponent's store.
     * Note that the new Komponent will be separate from the parent Komponent where this call is made. If you wanted to include the Elements as part of the parent, use `getElements()` instead.
     * For example:
     * <php>->getKomponent(PostForm::class, ['payload' => 'some-data'])->inModal()</php>.
     *
     * @param string     $methodName  The method name that will return a view or HTML response.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function getKomponent($komponentClass, $ajaxPayload = null)
    {
        //currently using same slot as method for kompoClass... why not?
        return $this->selfHttpRequest('POST', 'load-komponent', $komponentClass, $ajaxPayload);
    }

    //A temporary alias for the above method to avoid breaking changes for the developpers, will be deprecated in v4.
    public function getKomposer($komponentClass, $ajaxPayload = null)
    {
        return $this->getKomponent($komponentClass, $ajaxPayload);
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
     * @param string     $methodName  The class's method name that will return the new elements.
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
     * @param string     $methodName  The class's method name that will return the new elements.
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
     * @param string     $methodName  The class's method name that will return the new elements.
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
     * @param string     $methodName  The class's method name that will return the new elements.
     * @param array|null $ajaxPayload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function selfDelete($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('DELETE', 'self-method', $methodName, $ajaxPayload);
    }

    //TODO: document
    public function selfCreate($methodName, $ajaxPayload = null)
    {
        $this->applyToElement(
            fn($el) => $el->config(['refreshParent' => true])
        );

        return $this->selfGet($methodName, $ajaxPayload);
    }

    //TODO: document
    public function selfUpdate($methodName, $ajaxPayload = null)
    {
        return $this->selfCreate($methodName, $ajaxPayload);
    }




    //TODO: document
    public function setHistory($route, $parameters)
    {
        return $this->prepareAction('setHistory', [
            'setHistory' => RouteFinder::guessRoute($route, $parameters),
        ]);
    }
}
