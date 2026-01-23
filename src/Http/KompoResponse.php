<?php

namespace Kompo\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class KompoResponse
{
    /**
     * Return a modal response
     */
    public static function modal($content, $options = [])
    {
        return response()->json([
            'kompoResponseType' => 'modal',
            'content' => $content,
            'options' => array_merge([
                'transitionName' => 'modal',
                'warnBeforeClose' => false,
                'refreshParent' => false,
                'closeAfterSubmit' => true,
            ], $options)
        ]);
    }

    /**
     * Return a panel response
     */
    public static function panel($content, $panelId, $options = [])
    {
        return response()->json([
            'kompoResponseType' => 'panel',
            'content' => $content,
            'panelId' => $panelId,
            'options' => array_merge([
                'included' => null,
                'refreshParent' => false,
                'resetAfterSubmit' => true,
            ], $options)
        ]);
    }

    /**
     * Return a drawer response
     */
    public static function drawer($content, $options = [])
    {
        return response()->json([
            'kompoResponseType' => 'drawer',
            'content' => $content,
            'options' => array_merge([
                'warnBeforeClose' => false,
                'refreshParent' => false,
                'closeAfterSubmit' => true,
            ], $options)
        ]);
    }

    /**
     * Return a popup response
     */
    public static function popup($content, $options = [])
    {
        return response()->json([
            'kompoResponseType' => 'popup',
            'content' => $content,
            'options' => array_merge([
                'draggable' => false,
                'resizable' => false,
            ], $options)
        ]);
    }

    /**
     * Return a redirect response
     */
    public static function redirect($url, $options = [])
    {
        return response()->json([
            'kompoResponseType' => 'redirect',
            'url' => $url,
            'options' => $options
        ]);
    }

    /**
     * Return an alert response
     */
    public static function alert($message, $type = 'success', $options = [])
    {
        return response()->json([
            'kompoResponseType' => 'alert',
            'message' => $message,
            'type' => $type,
            'options' => $options
        ]);
    }

    /**
     * Return a refresh response
     *
     * @param string|array|null $kompoids Target component id(s), null for self-refresh
     * @param mixed $data Additional data to pass (optional)
     */
    public static function refresh($kompoids = null, $data = null)
    {
        return response()->json([
            'kompoResponseType' => 'refresh',
            'kompoids' => $kompoids,
            'data' => $data,
        ], 202);
    }

    /**
     * Update specific elements within a component by their IDs.
     * More efficient than full refresh when only some elements change.
     *
     * @param array $elements Array of element objects with IDs
     * @param string|null $kompoid Target component, null for current
     * @param string|null $transition Optional Vue transition name for animations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updateElements(array $elements, ?string $kompoid = null, ?string $transition = null)
    {
        // Prepare elements for JSON, keyed by their IDs
        $preparedElements = [];
        foreach ($elements as $element) {
            if (is_object($element) && property_exists($element, 'id') && $element->id) {
                $preparedElements[$element->id] = $element;
            }
        }

        return response()->json([
            'kompoResponseType' => 'updateElements',
            'kompoid' => $kompoid,
            'elements' => $preparedElements,
            'transition' => $transition,
        ], 202);
    }

    /**
     * Execute a JavaScript function on the frontend.
     *
     * @param string $jsFunction The function name or arrow function string
     * @param mixed $data Optional data to pass to the function
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function run(string $jsFunction, $data = null)
    {
        return response()->json([
            'kompoResponseType' => 'run',
            'jsFunction' => $jsFunction,
            'data' => $data,
        ]);
    }

    /**
     * Helper method to create a response with modal content
     * This can be used as an alternative to response()->modal()
     */
    public static function modalResponse($content, $options = [])
    {
        return static::modal($content, $options);
    }

    /**
     * Helper method to create a response with panel content
     * This can be used as an alternative to response()->panel()
     */
    public static function panelResponse($content, $panelId, $options = [])
    {
        return static::panel($content, $panelId, $options);
    }

    /**
     * Helper method to create a response with drawer content
     * This can be used as an alternative to response()->drawer()
     */
    public static function drawerResponse($content, $options = [])
    {
        return static::drawer($content, $options);
    }

    /**
     * Helper method to create a response with popup content
     * This can be used as an alternative to response()->popup()
     */
    public static function popupResponse($content, $options = [])
    {
        return static::popup($content, $options);
    }

    /**
     * Helper method to create a redirect response
     * This can be used as an alternative to response()->kompoRedirect()
     */
    public static function redirectResponse($url, $options = [])
    {
        return static::redirect($url, $options);
    }

    /**
     * Helper method to create an alert response
     * This can be used as an alternative to response()->kompoAlert()
     */
    public static function alertResponse($message, $type = 'success', $options = [])
    {
        return static::alert($message, $type, $options);
    }

    /**
     * Helper method to create a refresh response
     * This can be used as an alternative to response()->kompoRefresh()
     */
    public static function refreshResponse($kompoids = null, $data = null)
    {
        return static::refresh($kompoids, $data);
    }

    /**
     * Helper method to create an updateElements response
     * This can be used as an alternative to response()->kompoUpdateElements()
     */
    public static function updateElementsResponse(array $elements, ?string $kompoid = null, ?string $transition = null)
    {
        return static::updateElements($elements, $kompoid, $transition);
    }

    /**
     * Update specific elements globally by their IDs.
     * Unlike updateElements() which targets a komponent's element array,
     * this method targets elements directly by their ID anywhere in the app.
     *
     * Supports updating: label, label2, value, config, addClass, removeClass, state
     *
     * @param array $updates Array of element updates: ['element-id' => ['label' => 'new label'], ...]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updateElementValues(array $updates)
    {
        return response()->json([
            'kompoResponseType' => 'updateElementValues',
            'updates' => $updates,
        ]);
    }

    /**
     * Shorthand to update a single element's label/content.
     *
     * @param string $elementId The element ID to target
     * @param string $label The new label/content
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updateLabel(string $elementId, string $label)
    {
        return static::updateElementValues([
            $elementId => ['label' => $label],
        ]);
    }

    /**
     * Helper method to create a run response
     * This can be used as an alternative to response()->kompoRun()
     */
    public static function runResponse(string $jsFunction, $data = null)
    {
        return static::run($jsFunction, $data);
    }
}

