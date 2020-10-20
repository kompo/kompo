<?php

namespace Kompo\Interactions\Actions;

trait FillModalActions
{
    /**
     * Whatever is loaded by AJAX will be displayed in a modal. 
     * If the modalName is left blank, the default modal will be used. 
     * Otherwise, you have to declare a `&lt;vl-modal>` with the desired name.
     *
     * @param      string|null  $modalName  The modal name (optional)
     *
     * @return     self    
     */
    public function inModal($modalName = null)
    {
        return $this->prepareAction('fillModal', [
            'modalName' => $modalName,
            'panelId' => $modalName
        ]);
    }

    /**
     * New method to document
     *
     * @param      <type>  $komposerClass  The komposer class
     * @param      <type>  $ajaxPayload    The ajax payload
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function editInModal($komposerClass, $ajaxPayload = null)
    {
        $this->applyToElement(function($element) use($komposerClass, $ajaxPayload) {
            $element->getKomposer($komposerClass, $ajaxPayload);
        });

        return $this->prepareAction('insertModal');
    }
    
}