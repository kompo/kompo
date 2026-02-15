<?php

namespace Kompo;

use Kompo\Elements\Trigger;

class Th extends Trigger
{
    public $vueComponent = 'Th';

    /**
     * Add a column filter (dropdown or text input) to this header.
     *
     * @param string $name    The request parameter name for this filter.
     * @param array  $options Key => label for dropdown. Empty = text input.
     * @return self
     */
    public function filterBy($name, $options = [])
    {
        return $this->config([
            'filterName' => $name,
            'filterOptions' => $options,
        ]);
    }
}
