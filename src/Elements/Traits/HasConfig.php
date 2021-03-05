<?php

namespace Kompo\Elements\Traits;

trait HasConfig
{
    /**
     * The element's public config array.
     * Should never be overriden by inheritance.
     *
     * @var array
     */
    public $config = [];

    /**
     * Pass additional config to the element that can be accessed from the Front-end in the `config` property of the object - especially useful if you wish to customize or add new features to the component.
     *
     * @param array $config Key/value associative array.
     *
     * @return mixed
     */
    public function config($config = null)
    {
        if (is_array($config)) {
            $this->config = array_replace($this->config, $config);

            return $this;
        } else {
            return $config ? ($this->config[$config] ?? null) : $this->config;
        }
    }
}
