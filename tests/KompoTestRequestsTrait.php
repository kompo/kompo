<?php

namespace Kompo\Tests;

use Kompo\Core\KompoAction;
use Kompo\Core\KompoInfo;
use Kompo\Core\KompoTarget;

trait KompoTestRequestsTrait
{
    protected $kompoUri = '_kompo';

    /**** Kompo Requests & Actions *****/

    protected function getElements($komponent, $method)
    {
        return $this->kompoAction($komponent, 'include-elements', [], KompoTarget::getEncryptedArray($method));
    }

    protected function submit($komponent, $data = [])
    {
        return $this->kompoAction($komponent, $komponent->config('submitAction'), $data);
    }

    protected function browse($komponent, $data = [], $sort = null, $page = null)
    {
        return $this->kompoAction($komponent, 'browse-items', $data, [
            'X-Kompo-Page' => $page,
            'X-Kompo-Sort' => $sort,
        ]);
    }

    protected function searchOptions($komponent, $search, $method)
    {
        return $this->kompoAction($komponent, 'search-options', ['search' => $search], KompoTarget::getEncryptedArray($method));
    }

    protected function selfGet($komponent, $method, $data = [])
    {
        return $this->kompoAction($komponent, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'GET');
    }

    protected function selfPost($komponent, $method, $data = [])
    {
        return $this->kompoAction($komponent, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'POST');
    }

    protected function selfPut($komponent, $method, $data = [])
    {
        return $this->kompoAction($komponent, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'PUT');
    }

    protected function selfDelete($komponent, $method, $data = [])
    {
        return $this->kompoAction($komponent, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'DELETE');
    }

    protected function kompoAction($komponent, $action, $data, $headers = [], $method = 'POST')
    {
        return $this->withHeaders(array_merge(
            $headers,
            KompoInfo::arrayFromElement($komponent),
            KompoAction::headerArray($action)
        ))
        ->json($method, $this->kompoUri, $data);
    }

    protected function submitToRoute($form, $data = [])
    {
        return $this->withHeaders(
            KompoInfo::arrayFromElement($form)
        )->json('POST', $form->config('submitUrl'), $data);
    }

    /***** Dumper helpers ******/

    protected function getdd($uri)
    {
        dd($this->withoutExceptionHandling()->get($uri)->dump());
    }

    protected function submitdd($komponent, $data = [])
    {
        dd($this->withoutExceptionHandling()->submit($komponent, $data)->dump());
    }

    protected function browsedd($komponent, $data, $sort = null, $page = null)
    {
        dd($this->withoutExceptionHandling()->browse($komponent, $data)->dump());
    }

    protected function searchOptionsdd($komponent, $search, $method)
    {
        dd($this->withoutExceptionHandling()->searchOptions($komponent, $search, $method)->dump());
    }

    protected function selfGetdd($komponent, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfGet($komponent, $method, $data)->dump());
    }

    protected function selfPostdd($komponent, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfPost($komponent, $method, $data)->dump());
    }

    protected function selfPutdd($komponent, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfPut($komponent, $method, $data)->dump());
    }

    protected function selfDeletedd($komponent, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfDelete($komponent, $method, $data)->dump());
    }

    protected function submitToRoutedd($komponent, $route, $data = [])
    {
        dd($this->withoutExceptionHandling()->submitToRoute($komponent, $route, $data)->dump());
    }
}
