<?php

namespace Kompo\Elements\Traits;

trait HasHtmlAttributes
{
    /**
     * Sets HTML attributes to the element. For example:
     * <php>->attr([
     *     'title' => 'Some title',
     *     'data-target' => '#target-id'
     * ])</php>
     *
     * @param array|string|null $attributes Associative array of attribute names and values.
     *
     * @return self
     */
    public function attr($attributes = null)
    {
        if (is_array($attributes)) {
            return $this->config([
                'attrs' => array_replace($this->attr() ?: [], $attributes),
            ]);
        } else {
            return $attributes ? ($this->config['attrs'][$attributes] ?? null) : $this->config('attrs');
        }
    }

    /**
     * Sets the HTML title attribute of the element.
     *
     * @param string $title The HTML title attribute
     *
     * @return self
     */
    public function title($title)
    {
        return $this->attr([
            'title' => __($title),
        ]);
    }

    public function balloon($label, $position = 'up')
    {
        return $this->attr([
            'aria-label'       => __($label),
            'data-balloon-pos' => $position,
        ]);
    }
}
