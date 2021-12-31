<?php

namespace Kompo;

use Kompo\Elements\Traits\HasGutters;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Elements\Layout;
use Kompo\Elements\Traits\VerticalAlignmentTrait;

class Columns extends Layout
{
    use HasInteractions;
    use ForwardsInteraction;
    use VerticalAlignmentTrait;
    use HasGutters;

    public $vueComponent = 'Columns';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->breakpoint('md');
    }

    /**
     * The content will remain in columns no matter the viewport - i.e. the columns will not rearrange, even on mobile.
     *
     * @return self
     */
    public function notResponsive()
    {
        return $this->breakpoint();
    }

    /**
     * The columns will re-arrange at that specific breakpoint. The default breakpoint is 'md'.
     *
     * @param string $breakpoint A breakpoint value: 'xs', 'sm', 'md', 'lg', 'xl'.
     *
     * @return self
     */
    public function breakpoint($breakpoint = null)
    {
        $this->config(['breakpoint' => $breakpoint]);

        return $this;
    }
}
