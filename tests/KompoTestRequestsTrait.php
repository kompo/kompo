<?php

namespace Kompo\Tests;

trait KompoTestRequestsTrait
{
    protected $kompoUri = '_kompo';

    /**** Kompo Requests & Actions *****/

    protected function kompoPost($komposer, $method, $data = [])
    {
        return $this->kompoAction($komposer, 'post-to-form', $data, [
            'X-Kompo-Method' => $method
        ]);
    }

    protected function submit($komposer, $data = [])
    {
        return $this->kompoAction($komposer, $komposer->data('submitAction'), $data);
    }

    protected function browse($komposer, $data, $sort = null, $page = null)
    {
        return $this->kompoAction($komposer, 'browse-items', $data, [ 
            'X-Kompo-Page' => $page,
            'X-Kompo-Sort' => $sort,
        ]);
    }

    protected function searchOptions($komposer, $data)
    {
        return $this->kompoAction($komposer, 'search-options', $data);
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

    protected function searchOptionsdd($komposer, $data)
    {
        dd($this->withoutExceptionHandling()->searchOptions($komposer, $data)->dump());
    }

}