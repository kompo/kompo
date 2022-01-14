<?php

namespace Kompo\Exceptions;

use LogicException;

class NotAllowedLabelException extends LogicException
{
    public function __construct($label, $element)
    {
        $labelDescription = is_array($label) ? json_encode($label) : $label;

        parent::__construct("A label is not an allowed type on Element [".class_basename($element)."]. Label: {$labelDescription}");
    }
}
