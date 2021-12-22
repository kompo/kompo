<?php

namespace Kompo\Elements\Traits;

trait HasSelectedClass
{
    /**
     * Sets the class for the selected option.
     *
     * @param string      $selectedClass   The class(es) for the selected option
     * @param string|null $unselectedClass The class(es) for the unselected options
     *
     * @return self
     */
    public function selectedClass($selectedClass, $unselectedClass = '')
    {
        //TODO: add to kompo documentation
        return $this->config([
            'selectedClass'   => $selectedClass,
            'unselectedClass' => $unselectedClass,
        ]);
    }

    /**
     * Sets the class for both the selected and unselected states.
     *
     * @param string      $commonClass   The class(es) for the both selected and unselected states
     *
     * @return self
     */
    public function commonClass($commonClass)
    {
        //TODO: add to kompo documentation
        return $this->config([
            'commonClass'   => $commonClass,
        ]);
    }
}
