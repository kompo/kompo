<?php

namespace Kompo;

use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Elements\Layout;
use Kompo\Elements\Traits\HasHref;
use Kompo\Elements\Traits\VerticalAlignmentTrait;

class Flex extends Layout
{
    use HasInteractions;
    use ForwardsInteraction;
    use VerticalAlignmentTrait;
    use HasHref;

    public $vueComponent = 'Flex';
    public $bladeComponent = 'Flex';

    protected function initialize($label)
    {
        parent::initialize($label);

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
        $this->config(['justifyClass' => '']);

        return $this;
    }

    /**
     * Justify the content of the columns to the center.
     *
     * @return self
     */
    public function justifyCenter()
    {
        $this->config(['justifyClass' => 'justify-center']);

        return $this;
    }

    /**
     * Justify the content of the columns to the end.
     *
     * @return self
     */
    public function justifyEnd()
    {
        $this->config(['justifyClass' => 'justify-end']);

        return $this;
    }

    /**
     * Justify the content of the columns with space between.
     *
     * @return self
     */
    public function justifyBetween()
    {
        $this->config(['justifyClass' => 'justify-between']);

        return $this;
    }

    /**
     * Justify the content of the columns with space around.
     *
     * @return self
     */
    public function justifyAround()
    {
        $this->config(['justifyClass' => 'justify-around']);

        return $this;
    }
}
