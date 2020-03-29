<?php 

namespace Kompo\Core;

class RequestData
{
    public static function fieldShouldFilter($field)
    {
        return request(static::convertIn($field->data('filterKey')));
    }

    protected static function convertIn($name)
    {
        return str_replace('`', '.', $name);
    }

	protected static function convertOut($name)
    {
        return str_replace('.', '`', $name);
    }
}