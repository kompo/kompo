<?php

namespace Kompo\Elements\Traits;

trait HasStyles
{
    /**
     * The element's styles string.
     *
     * @var array
     */
    public $style = '';

    /**
     * Adds one or more ";"-separated styles to the element.
     *
     * @param string $styles
     *
     * @return mixed
     */
    public function addStyle($styles)
    {
        return $this->style(
            $this->style ?
            ($this->style.';'.trim($styles)) :
            $styles
        );
    }

    /**
     * Sets the style attribute of the element.
     * For multiple styles, use a ";" separated string.
     *
     * @param string $styles The CSS style attribute.
     *
     * @return mixed
     */
    public function style($styles = null)
    {
        if ($styles) {
            $this->style = trim($styles);

            return $this;
        } else {
            return $this->style;
        }
    }

    /**
     * Sets the style attribute for the input element of the field.
     * For multiple styles, use a ";" separated string.
     *
     * @param string $style The CSS style attribute.
     *
     * @return mixed
     */
    public function inputStyle($style)
    {
        return $this->config(['inputStyle' => $style]);
    }
}
