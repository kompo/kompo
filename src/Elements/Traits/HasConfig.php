<?php 

namespace Kompo\Elements\Traits;

trait HasConfig
{
    /**
     * The meta komponent's data for internal usage. Contains the store, route parameters, etc...
     *
     * @var array
     */
    protected $_kompo = [];

    /**
     * Assign or retrieve elements from the internal kompo config object.
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function _kompo($key, $data = null)
    {
        //ugly code to avoid adding polluting methods to the class

        if(in_array($key, ['modelKey', 'columns'])){ //not arrays: set if not found

            if($data === null){
                return $this->_kompo[$key] ?? null;
            }else{
                $this->_kompo[$key] = $data;
                return $this;
            }

        }elseif($key === 'fields'){ //storing field Komponents: push

            if(is_array($data)){
                array_push($this->_kompo[$key], $data);
                return $this;
            }else{
                return $this->_kompo[$key];
            }

        }else{ //not arrays: replace or add values if found

            if(is_array($data)){
                $this->_kompo[$key] = array_replace($this->_kompo[$key], $data);
                return $this;
            }else{
                return $data === null ? $this->_kompo[$key] : ($this->_kompo[$key][$data] ?? null);
            }

        }
    }

}