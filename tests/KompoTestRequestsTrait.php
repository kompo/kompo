<?php

namespace Kompo\Tests;

use Kompo\Core\KompoTarget;

trait KompoTestRequestsTrait
{
    protected $kompoUri = '_kompo';

    /**** Kompo Requests & Actions *****/

    protected function kompoPost($komposer, $method, $data = [])
    {
        return $this->kompoAction($komposer, 'post-to-form', $data, KompoTarget::getEncryptedArray($method));
    }

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

    protected function kompoAction($komposer, $action, $data, $headers = [])
    {
        return $this->withHeaders(array_merge($headers, [ 
            'X-Kompo-Id' => $komposer->data('kompoId'),
            'X-Kompo-Action' => $action
        ]))
        ->json('POST', $this->kompoUri, $data);
    }

    /***** Dumper helpers ******/

    protected function getdd($uri)
    {
        dd($this->withoutExceptionHandling()->get($uri)->dump());
    }

    protected function kompoPostdd($komposer, $data = [])
    {
        dd($this->withoutExceptionHandling()->kompoPost($komposer, $data)->dump());
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

}