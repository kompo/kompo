<?php 

namespace Kompo\Elements\Traits;

trait ElementHelperMethods 
{
    /*************************************************************************
    ** It is OK if these methods are overriden in Komposer classes... ********
    *************************************************************************/




    /**
     * Hides the field or element on the Front-end with Vue's v-if. 
     *
     * @return self
     */
    public function displayNone()
    {
        return $this->config(['displayNone' => true]);
    }


}