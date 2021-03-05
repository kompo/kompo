<?php

namespace Kompo\Komponents\Traits;

trait UsedInTables
{
    public function tdClass($tdClass)
    {
        return $this->config([
            'tdClass' => $tdClass,
        ]);
    }
}
