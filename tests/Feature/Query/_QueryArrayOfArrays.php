<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Query;

class _QueryArrayOfArrays extends Query
{
    use _CollectionFiltersTrait;

    public function query()
    {
        return collect($this->baseData)->map(function ($val) {
            return [
                'input'       => $val,
                'select'      => $val,
                'multiselect' => $val,
                'equal'       => $val,
                'greater'     => $val,
                'lower'       => $val,
                'like'        => $val,
                'startswith'  => $val,
                'endswith'    => $val,
                'between'     => $val,
                'in'          => $val,
            ];
        });
    }
}
