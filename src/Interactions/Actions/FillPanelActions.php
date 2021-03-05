<?php

namespace Kompo\Interactions\Actions;

trait FillPanelActions
{
    /**
     * Whatever is loaded by AJAX will be displayed in the panel with the specified id.
     * You need to add a `Panel` component to your Form or Query. For example:
     * <php>Button::form('Click')->loadsView('view-route')->inPanel('panel-id'),
     * Panel::form('panel-id')</php>.
     *
     * @param string $panelId The panel id attribute
     *
     * @return self
     */
    public function inPanel($panelId, $included = null)
    {
        return $this->prepareAction('fillPanel', [
            'panelId'  => $panelId,
            'included' => $included,
        ]);
    }

    public function inPanel1()
    {
        return $this->inPanel('VlPanel1');
    }

    public function inPanel2()
    {
        return $this->inPanel('VlPanel2');
    }

    public function inPanel3()
    {
        return $this->inPanel('VlPanel3');
    }

    public function inPanel4()
    {
        return $this->inPanel('VlPanel4');
    }

    public function inPanel5()
    {
        return $this->inPanel('VlPanel5');
    }
}
