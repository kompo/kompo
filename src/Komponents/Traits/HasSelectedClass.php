<?php

namespace Kompo\Komponents\Traits;

trait HasSelectedClass
{
    /**
     * Sets the class for the selected option.
     *
     * @param string $selectedClass The class(es) for the selected option
     * @param string|null $unselectedClass The class(es) for the unselected options
     *
     * @return self
     */
    public function selectedClass($selectedClass, $unselectedClass = '')
    {
        //TODO: add to kompo documentation
        return $this->config([
            'selectedClass' => $selectedClass,
            'unselectedClass' => $unselectedClass,
        ]);
    }
}
