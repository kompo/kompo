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
     * Add a filter to this table header column.
     *
     * When $options is provided (key => label array), renders a single-select dropdown.
     * When empty, renders a text input.
     *
     * @param string $name    The request parameter name for the filter value.
     * @param array  $options Optional key => label pairs for a dropdown select.
     *
     * @return self
     */
    public function filterBy($name, $options = [])
    {
        $this->config(['filterName' => $name]);

        if (!empty($options)) {
            $this->config(['filterOptions' => $options]);
        }

        return $this;
    }

    /**
     * Retrocompat alias for filterBy() — text input filter only.
     *
     * @param string $name The request parameter name for this filter.
     * @return self
     */
    public function filter($name)
    {
        return $this->filterBy($name);
    }
}
