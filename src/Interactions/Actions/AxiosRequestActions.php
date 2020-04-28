<?php

namespace Kompo\Interactions\Actions;

use Kompo\Core\KompoTarget;
use Kompo\Routing\RouteFinder;

trait AxiosRequestActions
{
    protected function prepareAxiosRequest($data)
    {  
        return $this->prepareAction('axiosRequest', array_merge($data, [
            'sessionTimeoutMessage' => __('sessionTimeoutMessage')
        ])); 
    }


    public function selfHttpRequest($requestType, $kompoAction, $methodName, $ajaxPayload = null)
    {
        return $this->prepareAxiosRequest(array_merge([
            'route' => RouteFinder::getKompoRoute($requestType, $ajaxPayload),
            'routeMethod' => $requestType,
            'kompoAction' => $kompoAction,
            'ajaxPayload' => $ajaxPayload
        ],
            KompoTarget::getEncryptedArray($methodName)
        ));
    }

    /**
     * Includes additional komponents from the server, which will be included in the Form data.
     * To display it, you should chain it with the methods `inModal` or `inPanel`, the containers in which the view will be displayed. For example:
     * <php>->getKomponents('newKomponentsMethod')->inPanel('panel-id')</php>
     * 
     *
     * @param  string  $methodName    The class's method name that will return the new komponents.
     * @param  array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     * 
     * @return self
     */
    public function getKomponents($methodName, $ajaxPayload = null)
    {
        $this->applyToElement(function($element) use($methodName) {
            $element->data([ 'includes' => $methodName ]);
        });

        return $this->selfHttpRequest('POST', 'include-komponents', $methodName, $ajaxPayload)
            ->data([ 'included' => true ]); //to tell Panel to include rather than separate form
    }

    /**
     * Loads a view through AJAX. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->loadView('route-of-view')->inModal()</php>
     *
     * @param      string  $methodName    The method name that will return a view or HTML response.
     * @param      array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function getKomposer($komposerClass, $ajaxPayload = null)
    {
        //currently using same slot as method for kompoClass... why not?
        return $this->selfHttpRequest('POST', 'load-komposer', $komposerClass, $ajaxPayload); 
    }

    /**
     * Loads a view through AJAX. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->loadView('route-of-view')->inModal()</php>
     *
     * @param      string  $methodName    The method name that will return a view or HTML response.
     * @param      array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function getView($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('POST', 'get-view', $methodName, $ajaxPayload);
    }


    /**
     * Loads a view with a GET ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->getView('get-route-of-view')->inModal()</php>
     *
     * @param  string  $methodName    The class's method name that will return the new komponents.
     * @param  array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function selfGet($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('GET', 'self-method', $methodName, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->postView('get-route-of-view')->inModal()</php>
     *
     * @param  string  $methodName    The class's method name that will return the new komponents.
     * @param  array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function selfPost($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('POST', 'self-method', $methodName, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->postView('get-route-of-view')->inModal()</php>
     *
     * @param  string  $methodName    The class's method name that will return the new komponents.
     * @param  array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function selfPut($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('PUT', 'self-method', $methodName, $ajaxPayload);
    }

    /**
     * Loads a view with a POST ajax request. 
     * To display the view in a container, you may chain it with the methods `inModal` or `inPanel`. For example: 
     * <php>->postView('get-route-of-view')->inModal()</php>
     *
     * @param  string  $methodName    The class's method name that will return the new komponents.
     * @param  array|null  $ajaxPayload  Additional custom data to add to the request (optional).
     *
     * @return     self   
     */
    public function selfDelete($methodName, $ajaxPayload = null)
    {
        return $this->selfHttpRequest('DELETE', 'self-method', $methodName, $ajaxPayload);
    }
    
    
}