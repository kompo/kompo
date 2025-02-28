<?php

namespace Kompo\Komponents\Traits;

trait ScrollToOnLoadTrait 
{
    protected $scrollToId;    

    public function activateScroll($idPrefix, $container, $timeout = 800)
    {
        if($this->query->count() > 1) {

            $this->scrollToId = $this->scrollToId ?: $this->query->first()['attributes']['id'];

            $this->onLoad(fn($e) => $e->scrollTo($idPrefix.$this->scrollToId, 300, [
                'container'   => $container,
            ], $timeout));
        }
    }
}