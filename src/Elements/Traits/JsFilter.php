<?php

namespace Kompo\Elements\Traits;

trait JsFilter
{
    /**
     * Filter elements in a container based on this input
     * Usage: _Input('Search')->jsFilter('items-container', 'data-name')
     */
    public function jsFilter($containerId, $attribute = 'data-filter')
    {
        return $this->config([
            'jsFilter' => [
                'container' => $containerId,
                'attribute' => $attribute,
            ]
        ]);
    }

    /**
     * Mark this element as filterable
     * Usage: _Card(...)->jsFilterable($item->name)
     */
    public function jsFilterable($value)
    {
        return $this->data(['filter' => $value]);
    }
}
