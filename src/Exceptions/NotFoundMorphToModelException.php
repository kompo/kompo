<?php

namespace Kompo\Exceptions;

use RuntimeException;

class NotFoundMorphToModelException extends RuntimeException
{
    public function __construct($relation)
    {
        parent::__construct("No morphTo model was specified for field with relation [{$relation}]. Please use `->morphToModel(Model::class)` to specify which Model(s) is being morphed.");
    }
}
