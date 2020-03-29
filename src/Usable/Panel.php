<?php

namespace Kompo;

use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Interactions\Traits\NestsInteractions;
use Kompo\Komponents\Layout;

class Panel extends Layout
{
    use HasInteractions, NestsInteractions;
    
    public $component = 'FormPanel';

    protected function vlInitialize($labelAsId)
    {
        $this->id('Vl'.$labelAsId);
    }

    /**
     * Hides an element after the content is loaded in the Panel.
     *
     * @param      string  $hidingId  The id of the element to be hidden.
     *
     * @return self   
     */
    public function hidesOnLoad($hideId)
    {
        $this->data(['hidesOnLoad' => $hideId]);
        return $this;
    }

}
