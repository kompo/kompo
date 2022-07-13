<?php

namespace Kompo\Elements\Traits;

trait FocusBlur
{
    /**
     * TODO: document.
     */
    public function focusOnLoad()
    {
        return $this->config([
            'focusOnLoad' => true,
        ]);
    }
}
