<?php

namespace Kompo\Interactions\Actions;

trait FillPanelActions
{
    /**
     * Whatever is loaded by AJAX will be displayed in the panel with the specified id. 
     * You need to add a `Panel` component to your Form or Catalog. For example:
     * <php>Button::form('Click')->loadsView('view-route')->inPanel('panel-id'),
     * Panel::form('panel-id')</php>
     * You may also add an optional quick HTML response as a second parameter.
     * 
     *
     * @param      string  $panelId  The panel id attribute
     * @param      string|null  $response  An quick html response (optional)
     *
     * @return     self  
     */
    public function inPanel($panelId, $response = null)
    {
        return $this->prepareAction('fillPanel', [
            'panelId' => $panelId,
            'response' => $response
        ]);
    }

    public function inPanel1(){ return $this->inPanel('VlPanel1'); }
    public function inPanel2(){ return $this->inPanel('VlPanel2'); }
    public function inPanel3(){ return $this->inPanel('VlPanel3'); }
    public function inPanel4(){ return $this->inPanel('VlPanel4'); }
    public function inPanel5(){ return $this->inPanel('VlPanel5'); }
    
}