<?php 

namespace Kompo\Komponents\Traits;

trait HasHtmlAttributes {

    /**
     * Sets HTML attributes to the element. For example:
     * <php>->attr([
     *     'title' => 'Some title',
     *     'data-target' => '#target-id'
     * ])</php>
     * 
     *
     * @param      array  $attributes     Associative array of attribute names and values.
     *
     * @return     self
     */
    public function attr($attributes)
    {
        return $this->data([
            'attrs' => $attributes
        ]);
    }

    /**
     * Sets the HTML title attribute of the element. 
     * 
     *
     * @param      string  $title    The HTML title attribute
     *
     * @return     self
     */
    public function title($title)
    {
        return $this->attr([
            'title' => $title
        ]);
    }


    public function balloon($label, $position = 'up')
    {
        return $this->attr([
            'aria-label' => $label,
            'data-balloon-pos' => $position
        ]);
    }
}