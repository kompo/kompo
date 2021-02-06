<?php

namespace Kompo;

class StripeButton extends Button
{	
    /* 
     * https://stripe.com/docs/checkout/integration-builder
     * This is for stripe checkout experience
     * Uses composer require stripe/stripe-php (not laravel cashier)
     * Uses success/cancel redirects
     */

    public $vueComponent = 'StripeButton';

    public function mounted($komposer)
    {
        $this->emitDirect('stripeRedirect');
    }
}
