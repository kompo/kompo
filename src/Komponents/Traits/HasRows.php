<?php

namespace Kompo\Komponents\Traits;

trait HasRows
{
    /**
     * Sets the initial number of rows for the textarea. Default is 3.
     *
     * @param int $rows The number of rows
     *
     * @return self
     */
    public function rows($rows = 3)
    {
        return $this->config([
            'rows' => $rows,
        ]);
    }
}
