<?php

namespace Kompo;

use Kompo\Elements\Layout;
use Kompo\Elements\Managers\LayoutManager;

class Card extends Layout
{
    public $vueComponent = 'Card';

    public function __construct(...$args)
    {
        $this->vlInitialize(LayoutManager::getNormalizedLabel($args, $this));

        $this->elements = LayoutManager::collectFilteredElements($args, $this)->all(); //no ->values() chained to preserve 'keys' (but Layout has it)
    }

    /**
     * Override the default Vue component template.
     * Note that the Vue component file name will have 'Vl' apprended. For example, a $component of 'EditLink' will point to the file VlEditLink.vue.
     *
     * @param string $component The vue component name.
     *
     * @return self
     */
    public function component($component)
    {
        $this->vueComponent = $component;

        return $this;
    }

    /**
     * TODO: document when this is needed.
     *
     * @param <type> $key The key
     *
     * @return <type>
     */
    public function prop($key)
    {
        return $this->elements[$key];
    }
}
