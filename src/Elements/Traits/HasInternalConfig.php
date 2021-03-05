<?php

namespace Kompo\Elements\Traits;

use Kompo\Komponents\Field;

trait HasInternalConfig
{
    /**
     * The komposer / komponent's config for internal usage. Contains the store, route parameters, etc...
     *
     * @var array
     */
    protected $_kompo = [];

    /**
     * Assign or retrieve elements from the internal kompo config object.
     *
     * @param mixed $config
     *
     * @return mixed
     */
    public function _kompo($key, $config = null)
    {
        //ugly code to avoid adding polluting methods to the class

        if (in_array($key, ['modelKey', 'currentPage'])) { //not arrays: set if not found

            if ($config === null) {
                return $this->_kompo[$key] ?? null;
            } else {
                $this->_kompo[$key] = $config;

                return $this;
            }
        } elseif ($key === 'fields') { //storing field Komponents: push

            if ($config instanceof Field) {
                array_push($this->_kompo[$key], $config);

                return $this;
            } elseif (is_integer($config)) {
                unset($this->_kompo[$key][$config]);

                return $this;
            } else {
                return $this->_kompo[$key];
            }
        } else { //not arrays: replace or add values if found

            if (is_array($config)) {
                $this->_kompo[$key] = array_replace($this->_kompo[$key], $config);

                return $this;
            } else {
                return $config === null ? $this->_kompo[$key] : ($this->_kompo[$key][$config] ?? null);
            }
        }
    }
}
