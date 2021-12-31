<?php

namespace Kompo;

class Liste extends Input
{
    public $vueComponent = 'Liste';

    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var bool
     */
    protected $castsToArray = true;

    const DEFAULT_KEY_LABEL = 'key';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->inputType('text');
        $this->keyLabel(self::DEFAULT_KEY_LABEL);
    }

    public function mounted($form)
    {
        $this->config(['emptyValue' => $this->emptyValue()]);
    }

    protected function emptyValue()
    {
        return [[$this->config('keyLabel') => '']];
    }

    public function value($value)
    {
        $this->value = is_string($value) ? json_decode($value, true) : $value;

        return $this;
    }

    /**
     * Sets the key label for the JSON string that will be encoded in the DB.
     * The default is 'key'.
     *
     * @param string $keyLabel The key label for the json Object.
     *
     * @return self
     */
    public function keyLabel($keyLabel)
    {
        $this->config(['keyLabel' => $keyLabel]);

        return $this;
    }
}
