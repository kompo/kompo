<?php

namespace Kompo\Tests;

trait KompoTestRequestsTrait
{
    protected $kompoRoute = '_kompo';

    /**** Kompo Requests & Actions *****/

    protected function submit($komposer, $data)
    {
        return $this->kompoAction($komposer, 'eloquent-submit', $data);
    }

    protected function kompoAction($komposer, $action, $data)
    {
        return $this->withHeaders([ 
            'X-Kompo-Id' => $komposer->data('kompoId'),
            'X-Kompo-Action' => $action
        ])
        ->json('POST', $this->kompoRoute, $data);
    }

    /***** Dumper helpers ******/

    protected function getdd($uri)
    {
        dd($this->withoutExceptionHandling()->get($uri)->dump());
    }

    protected function submitdd($komposer, $data)
    {
        dd($this->withoutExceptionHandling()->submit($komposer, $data)->dump());
    }

}