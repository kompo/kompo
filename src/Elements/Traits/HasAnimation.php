<?php 

namespace Kompo\Elements\Traits;

trait HasAnimation {

    /**
     * Pass additional data to the element that can be accessed from the Front-end in the `data` property of the object - especially useful if you wish to customize or add new features to the component.
     *
     * @param  string  $animation The transition name.
     * @param  string|null  $mode The transition mode (optional). Default is simultaneous. Other choices: 'out-in' or 'in-out'
     * @return mixed
     */
    public function animate($animation, $mode = '')
    {
        $this->config([
            'transition' => $animation,
            'transitionMode' => $mode
        ]);
        return $this;
    }

}