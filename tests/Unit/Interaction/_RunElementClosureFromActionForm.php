<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Button;
use Kompo\Form;
use Kompo\Input;

class _RunElementClosureFromActionForm extends Form
{
    public function komponents()
    {
    	return [
    		Button::form()->getKomponents('includeMethod'),

            Button::form()->on('click', function($e){
                $e->get('someView')->getKomponents('includeMethod');
            }),

            Button::form()->post('something')->onSuccess(function($e){
                $e->getKomponents('includeMethod');
            }),

            Button::form()->on('click', function($e){

                $e->submit()->put('dea');
                
                $e->post('someView')->onSuccess(function($e){

                    $e->inPanel('cs')->onSuccess(function($e){

                        $e->getKomponents('includeMethod');

                    });

                });

            })

    	];
    }

    public function includeMethod(_InteractionPostRequest $request)
    {
    	return [
            //some komponents
        ];
    }
	
}