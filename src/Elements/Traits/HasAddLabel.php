<?php

namespace Kompo\Elements\Traits;

trait HasAddLabel
{
    /**
     * Specifies the label of the link that will load the form. Default is 'Add a new option'.
     *
     * @param string|Kompo\Elements\Element  $label The add label text or full element if you want to customize it
     * @param string|null $icon The add label icon - DO NOT USE, will deprecate in v3
     * @param string|null $class The add label class - DO NOT USE, will deprecate in v3
     */
    public function addLabel($label, ?string $icon = 'icon-plus', ?string $class = '')
    {
        $addedElement = is_string($label) ? _Link($label)->icon($icon)->class($class) : $label;

        return $this->config([
            'addLabel' => $addedElement->emitDirect('newitem')
        ]);
    }
}
