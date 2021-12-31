<?php

namespace Kompo;

use Kompo\Elements\Field;

class Stripe extends Field
{
    /* TODO: rename
     * https://stripe.com/docs/payments/integration-builder
     * This is for custom checkout experience (build your own form)
     * Uses laravel cashier
     * Uses payment intent creation from stripe
     */

    public $vueComponent = 'Stripe';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->noLabel();
    }

    public function withIntent($intent)
    {
        $this->config(['intent' => $intent]);

        return $this;
    }

    public function withCardholder($label = 'Cardholder Name')
    {
        $this->config([
            'cardholderLabel' => __($label),
            'cardholderError' => __('cardholderError'),
        ]);

        return $this;
    }

    public function fontSrc($fontSrc)
    {
        $this->config(['fontSrc' => $fontSrc]);

        return $this;
    }

    public function stripeStyles($styles)
    {
        $this->config(['styles' => $styles]);

        return $this;
    }
}
