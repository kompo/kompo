<?php

namespace Kompo;

use Kompo\Elements\Trigger;

class Th extends Trigger
{
    public $vueComponent = 'Th';

    /**
     * Add a multi-select slicer (checkboxes) to this header's dropdown.
     *
     * @param string $name    The request parameter name for this slicer.
     * @param array  $options Key => label pairs. If empty, values are extracted from visible cells.
     * @return self
     */
    public function slicer($name, $options = [])
    {
        return $this->config([
            'slicerName' => $name,
            'slicerOptions' => $options,
        ]);
    }

    /**
     * Add a text filter input to this header's dropdown.
     *
     * @param string $name The request parameter name for this filter.
     * @return self
     */
    public function filterHeader($name)
    {
        return $this->config([
            'filterName' => $name,
        ]);
    }
}
