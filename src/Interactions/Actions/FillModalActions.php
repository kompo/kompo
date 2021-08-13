<?php

namespace Kompo\Interactions\Actions;

trait FillModalActions
{
    /**
     * Whatever is loaded by AJAX will be displayed in a modal.
     * If the modalName is left blank, the default modal will be used.
     * Otherwise, you have to declare a `&lt;vl-modal>` with the desired name.
     *
     * @param string|null $modalName The modal name (optional)
     *
     * @return self
     */
    public function inModal($modalName = null)
    {
        return $this->prepareAction('fillModal', [
            'modalName' => $modalName,
            'panelId'   => $modalName,
        ]);
    }

    /**
     * TODO: New method to document and add.
     *
     * @param <type> $route       The route
     * @param <type> $parameters  The parameters
     * @param <type> $ajaxPayload The ajax payload
     *
     * @return <type> ( description_of_the_return_value )
     */
    public function editInModal($route, $parameters = null, $ajaxPayload = null)
    {
        $this->applyToElement(function ($el) use ($route, $parameters, $ajaxPayload) {
            $el->class('cursor-pointer')
                ->get($route, $parameters, $ajaxPayload);
        });

        return $this->prepareAction('modalInsert');
    }
}
