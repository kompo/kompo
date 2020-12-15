<?php

namespace Kompo;

use Kompo\Core\IconGenerator;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Komponents\Layout;

class Panel extends Layout
{
    use HasInteractions, ForwardsInteraction;
    
    public $vueComponent = 'FormPanel';

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

    /**
     * Adds a closing button.
     *
     * @param string|null  $label  The label of the close button
     *
     * @return self   
     */
    public function closable($label = null, $icon = 'icon-times')
    {
        $this->data([
            'closable' => IconGenerator::toHtml($icon).' '.$label,
        ]);
        return $this;
    }

}
