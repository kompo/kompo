<?php

namespace Kompo\Komponents\Traits;

trait HasAddLabel
{
    /**
     * Specifies the label of the link that will load the form. Default is 'Add a new option'.
     *
     * @param string|Kompo\Komponents\Komponent  $labelOrKomponent The add label text or full komponent if you want to customize it
     * @param string|null $icon The add label icon - DO NOT USE, will deprecate in v3
     * @param string|null $class The add label class - DO NOT USE, will deprecate in v3
     */
    public function addLabel($labelOrKomponent, ?string $icon = 'icon-plus', ?string $class = '')
    {
        $addingKomponent = is_string($labelOrKomponent) ? 
                                
                                _Link($labelOrKomponent)->icon($icon)->class($class) : 

                                $labelOrKomponent;

        return $this->config([
            'addLabel' => $addingKomponent->emitDirect('newitem')
        ]);
    }
}
