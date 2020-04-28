<?php

namespace Kompo\Tests;

use Kompo\Core\KompoTarget;

trait KompoTestRequestsTrait
{
    protected $kompoUri = '_kompo';

    /**** Kompo Requests & Actions *****/

    protected function getKomponents($komposer, $method)
    {
        return $this->kompoAction($komposer, 'include-komponents', [], KompoTarget::getEncryptedArray($method));
    }

    protected function submit($komposer, $data = [])
    {
        return $this->kompoAction($komposer, $komposer->data('submitAction'), $data);
    }

    protected function browse($komposer, $data = [], $sort = null, $page = null)
    {
        return $this->kompoAction($komposer, 'browse-items', $data, [ 
            'X-Kompo-Page' => $page,
            'X-Kompo-Sort' => $sort,
        ]);
    }

    protected function searchOptions($komposer, $search, $method)
    {
        return $this->kompoAction($komposer, 'search-options', ['search' => $search], KompoTarget::getEncryptedArray($method));
    }

    protected function selfGet($komposer, $method, $data = [])
    {
        return $this->kompoAction($komposer, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'GET');
    }

    protected function selfPost($komposer, $method, $data = [])
    {
        return $this->kompoAction($komposer, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'POST');
    }

    protected function selfPut($komposer, $method, $data = [])
    {
        return $this->kompoAction($komposer, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'PUT');
    }

    protected function selfDelete($komposer, $method, $data = [])
    {
        return $this->kompoAction($komposer, 'self-method', $data, KompoTarget::getEncryptedArray($method), 'DELETE');
    }

    protected function kompoAction($komposer, $action, $data, $headers = [], $method = 'POST')
    {
        return $this->withHeaders(array_merge($headers, [ 
            'X-Kompo-Info' => $komposer->data('kompoInfo'),
            'X-Kompo-Action' => $action
        ]))
        ->json($method, $this->kompoUri, $data);
    }

    /***** Dumper helpers ******/

    protected function getdd($uri)
    {
        dd($this->withoutExceptionHandling()->get($uri)->dump());
    }

    protected function submitdd($komposer, $data = [])
    {
        dd($this->withoutExceptionHandling()->submit($komposer, $data)->dump());
    }

    protected function browsedd($komposer, $data, $sort = null, $page = null)
    {
        dd($this->withoutExceptionHandling()->browse($komposer, $data)->dump());
    }

    protected function searchOptionsdd($komposer, $search, $method)
    {
        dd($this->withoutExceptionHandling()->searchOptions($komposer, $search, $method)->dump());
    }

    protected function selfGetdd($komposer, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfGet($komposer, $method, $data)->dump());
    }

    protected function selfPostdd($komposer, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfPost($komposer, $method, $data)->dump());
    }

    protected function selfPutdd($komposer, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfPut($komposer, $method, $data)->dump());
    }

    protected function selfDeletedd($komposer, $method, $data = [])
    {
        dd($this->withoutExceptionHandling()->selfDelete($komposer, $method, $data)->dump());
    }

}