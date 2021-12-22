<?php

namespace Kompo\Elements\Traits;

trait UsedInTables
{
    public function tdClass($tdClass)
    {
        return $this->config([
            'tdClass' => $tdClass,
        ]);
    }
}
