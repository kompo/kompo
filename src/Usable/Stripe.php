<?php

namespace Kompo;

use Kompo\Komponents\Field;

class Stripe extends Field
{
    public $vueComponent = 'Stripe';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

    	$this->noLabel();
    }

    public function withIntent($intent)
    {
    	$this->data(['intent' => $intent]);
    	return $this;
    }

    public function withCardholder($label = 'Cardholder Name')
    {
    	$this->data([
    		'cardholderLabel' => __($label),
    		'cardholderError' => __('cardholderError')
    	]);
    	return $this;

    }

    public function fontSrc($fontSrc)
    {
    	$this->data(['fontSrc' => $fontSrc]);
    	return $this;
    }

    public function stripeStyles($styles)
    {
    	$this->data(['styles' => $styles]);
    	return $this;
    }
}
