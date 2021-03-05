<?php

namespace Kompo\Komponents\Traits;

use Kompo\Core\IconGenerator;

trait HasAddLabel
{
    /**
     * Specifies the label of the link that will load the form. Default is 'Add a new option'.
     *
     * @param string      $label The add label text
     * @param string|null $class The add label class
     */
    public function addLabel(string $label, ?string $icon = 'icon-plus', string $class = '')
    {
        return $this->config([
            'addLabel'      => IconGenerator::toHtml($icon).' '.__($label),
            'addLabelClass' => $class,
        ]);
    }
}
