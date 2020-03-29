<?php 

namespace Kompo\Komponents\Traits;

trait TriggerStyles {

    /**
     * Sets the outlined style.
     *
     * @return     self 
     */
    public function outlined()
    {
        $this->data(['btnOutlined' => true]);
        $this->button();
        return $this;
    }

    /**
     * Sets the plain style.
     *
     * @return     self 
     */
    public function plain()
    {
        $this->data(['btnPlain' => true]);
        return $this;
    }

    /**
     * Makes an inline element display as block.
     *
     * @return     self 
     */
    public function block()
    {
        $this->data(['btnBlock' => true]);
        return $this;
    }

    /**
     * Makes a block element display as  inline.
     *
     * @return     self 
     */
    public function inline()
    {
        $this->data(['btnInline' => true]);
        return $this;
    }

    /**
     * Makes a non-button element look like a button.
     *
     * @return     self 
     */
    public function button()
    {
        $this->data(['btnStyle' => true]);
        return $this;
    }

    /**
     * Assigns the secondary colors. To modify the color, you need to set the $secondary variable in sass.
     *
     * @return     self 
     */
    public function secondary()
    {
        $this->data(['secondary' => true]);
        return $this;
    }
    
}