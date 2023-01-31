<?php

namespace Kompo\Interactions\Actions;

trait FillModalActions
{
    /**
     * The returned AJAX response (wether you are returning a komponent or an array of elements) will be displayed in a modal.
     *
     * @return self
     */
    public function inModal()
    {
        return $this->prepareAction('fillModalNew');
    }

    /** TODO document
     * The request displays in the already open modal.
     *
     * @return self
     */
    public function inLastModal()
    {
        $this->applyToElement(function ($el) {
            $el->closeLastModal();
        });

        return $this->inModal();
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
