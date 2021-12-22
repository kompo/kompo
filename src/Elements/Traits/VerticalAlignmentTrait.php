<?php

namespace Kompo\Elements\Traits;

trait VerticalAlignmentTrait
{
    /**
     * Centers the content vertically in the columns on md screens only.
     * <u>Note</u> This is the default setting, no need to set it initially.
     *
     * @return self
     */
    public function centerVertically()
    {
        $this->config(['alignClass' => 'vlAlignCenterMd']);

        return $this;
    }

    /**
     * Aligns the items vertically to the top.
     *
     * @return self
     */
    public function alignStart()
    {
        $this->config(['alignClass' => 'vlAlignStart']);

        return $this;
    }

    /**
     * Centers the items vertically.
     *
     * @return self
     */
    public function alignCenter()
    {
        $this->config(['alignClass' => 'vlAlignCenter']);

        return $this;
    }

    /**
     * Aligns the items vertically to the bottom.
     *
     * @return self
     */
    public function alignEnd()
    {
        $this->config(['alignClass' => 'vlAlignEnd']);

        return $this;
    }

    /**
     * Aligns the items according to the baseline.
     *
     * @return self
     */
    public function alignBaseline()
    {
        $this->config(['alignClass' => 'vlAlignBaseline']);

        return $this;
    }

    /**
     * Stretches the items vertically.
     *
     * @return self
     */
    public function alignStretch()
    {
        $this->config(['alignClass' => 'vlAlignStretch']);

        return $this;
    }
}
