<?php 

namespace Kompo\Elements\Traits;

trait IsMountable 
{

    /**
     * A method that gets executed after the component has been prepared.
     *
     * @param mixed $parameter
     * @return self
     */
    public function mountedHook($parameter = null)
    {
        if(method_exists($this, 'mounted'))
            $this->mounted($parameter);
        return $this;
    }

}