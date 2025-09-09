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
     */
    public static function refresh($data = null)
    {
        return response()->json([
            'kompoResponseType' => 'refresh',
            'data' => $data
        ], 202);
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
    public static function refreshResponse($data = null)
    {
        return static::refresh($data);
    }
}

