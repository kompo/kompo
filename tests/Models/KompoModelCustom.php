<?php

namespace Kompo\Tests\Models;

use Kompo\Model;

class KompoModelCustom extends Model
{	
    const CREATED_BY = 'custom_created_by';
    const UPDATED_BY = 'custom_updated_by';

    protected $table = 'kompo_models';
}
