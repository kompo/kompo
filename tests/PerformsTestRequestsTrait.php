<?php

namespace Kompo\Tests;

trait PerformsTestRequestsTrait
{
    protected $kompoRoute = '_kompo';

    protected function submit($komposer, $data)
    {
        return $this->kompoAction($komposer, 'eloquent-submit', $data);
    }

    protected function submitdd($komposer, $data)
    {
        dd($this->withoutExceptionHandling()->submit($komposer, $data)->dump());
    }

    protected function kompoAction($komposer, $action, $data)
    {
        return $this->withHeaders([ 
            'X-Vuravel-Id' => $komposer->data('kompoId'),
            'X-Kompo-Action' => $action
        ])
        ->json('POST', $this->kompoRoute, $data);
    }

}