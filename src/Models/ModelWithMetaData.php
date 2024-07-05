<?php

namespace Kompo\Models;

class ModelWithMetaData extends ModelBase
{
    use \Kompo\Models\Traits\HasAddedModifiedByTrait;
    use \Illuminate\Database\Eloquent\SoftDeletes;
}
