<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Button;
use Kompo\Form;

class _RunElementClosureFromActionForm extends Form
{
    public function render()
    {
        return [
            Button::form()->getElements('includeMethod'),

            Button::form()->on('click', function ($e) {
                $e->get('someView')->getElements('includeMethod');
            }),

            Button::form()->post('something')->onSuccess(function ($e) {
                $e->getElements('includeMethod');
            }),

            Button::form()->on('click', function ($e) {
                $e->submit()->put('dea');

                $e->post('someView')->onSuccess(function ($e) {
                    $e->inPanel('cs')->onSuccess(function ($e) {
                        $e->getElements('includeMethod');
                    });
                });
            }),

        ];
    }

    public function includeMethod(_InteractionPostRequest $request)
    {
        return [
            //some elements
        ];
    }
}
