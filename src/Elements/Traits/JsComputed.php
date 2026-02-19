<?php

namespace Kompo\Elements\Traits;

trait JsComputed
{
    /**
     * Make this element display a computed value based on other fields
     * Usage: ->jsComputed(['price', 'quantity'], 'price * quantity')
     */
    public function jsComputed(array $watchFields, string $expression)
    {
        return $this->config([
            'jsComputed' => [
                'watch' => $watchFields,
                'expression' => $expression,
            ]
        ]);
    }

    /**
     * Format computed value
     * Usage: ->jsComputedFormat(['price'], 'price', 'currency')
     */
    public function jsComputedFormat(array $watchFields, string $expression, string $format = 'number')
    {
        $formatters = [
            'currency' => "new Intl.NumberFormat('en-US', {style:'currency',currency:'USD'}).format(%s)",
            'number' => "new Intl.NumberFormat().format(%s)",
            'percent' => "(%s * 100).toFixed(1) + '%'",
        ];

        $formatted = sprintf($formatters[$format] ?? '%s', $expression);

        return $this->config([
            'jsComputed' => [
                'watch' => $watchFields,
                'expression' => $formatted,
            ]
        ]);
    }
}
