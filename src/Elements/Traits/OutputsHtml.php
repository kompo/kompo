<?php

namespace Kompo\Elements\Traits;

trait OutputsHtml
{
    public function __toHtml()
    {
        return '<'.$this->htmlTag.
            ($this->class() ? (' class="'.$this->class().'"') : '').
            ($this->style() ? (' style="'.$this->style().'"') : '').
            collect($this->config('attrs'))->map(
                fn ($attrVal, $attrKey) => ' '.$attrKey.'="'.$attrVal.'"'
            )->implode('').
        '>'.$this->label.'</'.$this->htmlTag.'>';
    }
}
