<?php 

namespace Kompo\Elements\Traits;

trait HasDuskSelector {

    /**
     * Sets a dusk selector attribute to the element.      * 
     *
     * @param      array  $selector     Dusk selector attribute.
     *
     * @return     self
     */
    public function dusk($selector)
    {
        return $this->attr([
            'dusk' => $selector
        ]);
    }

}