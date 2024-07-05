<?php 

namespace Kompo\Models\Traits;

trait HasRelationType
{
    /* CALCULATED FIELDS */
    public static function getRelationType()
    {
        $className = class_basename(new static());

        return strtolower(\Str::snake($className, '-'));
    }
}