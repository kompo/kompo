<?php

namespace Kompo;

use Kompo\Komponents\Layout;
use Kompo\Komponents\Traits\VerticalAlignmentTrait;

class Flex extends Layout
{
    use VerticalAlignmentTrait;

    public $component = 'Flex';
    public $menuComponent = 'Flex';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->justifyStart();

        $this->alignCenter();
    }

    /**
     * Justify the content of the columns to the start.
     *
     * @return self 
     */
    public function justifyStart()
    {
        $this->data(['justifyClass' => '']);
        return $this;
    }


    /**
     * Justify the content of the columns to the center.
     *
     * @return self 
     */
    public function justifyCenter()
    {
        $this->data(['justifyClass' => 'vlJustifyCenter']);
        return $this;
    }


    /**
     * Justify the content of the columns to the end.
     *
     * @return self 
     */
    public function justifyEnd()
    {
        $this->data(['justifyClass' => 'vlJustifyEnd']);
        return $this;
    }


    /**
     * Justify the content of the columns with space between.
     *
     * @return self 
     */
    public function justifyBetween()
    {
        $this->data(['justifyClass' => 'vlJustifyBetween']);
        return $this;
    }


    /**
     * Justify the content of the columns with space around.
     *
     * @return self 
     */
    public function justifyAround()
    {
        $this->data(['justifyClass' => 'vlJustifyAround']);
        return $this;
    }
}
