<?php

namespace Kompo\Models;

use Illuminate\Database\Eloquent\Model as LaravelModel;

class ModelBase extends LaravelModel
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Kompo\Models\Traits\HasRelationType;

    public const DISPLAY_ATTRIBUTE = null; //OVERRIDE IN CLASS
    public const SEARCHABLE_NAME_ATTRIBUTE = null; //OVERRIDE IN CLASS

    /* CALCULATED FIELDS */
    public static function getNameDisplayKey()
    {
        return static::DISPLAY_ATTRIBUTE ?: static::SEARCHABLE_NAME_ATTRIBUTE;    
    }

    public function getNameDisplay()
    {
        $nameDisplayKey = $this->getNameDisplayKey() ?: 'name';

        return $this->{$nameDisplayKey};
    }
}
