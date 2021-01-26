<?php

namespace Kompo\Interactions\Actions;

trait RunJsActions
{
    //TODO: document
    public function run($jsFunction)
    {
        return $this->prepareAction('runJs', [
            'jsFunction' => $jsFunction
        ]);
    }

    //TODO: document
    public function scrollTo($selector, $duration, $options = [])
    {
    	return $this->prepareAction('scrollTo', [
    		'scrollSelector' => $selector,
    		'scrollDuration' => $duration,
    		'scrollOptions' => $options
    	]);
    }
}