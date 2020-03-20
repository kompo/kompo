<?php 
namespace Kompo\Komponents\Traits;

trait EloquentField 
{
    
    /**
     * Removes a specific field from the database interaction process.
     *
     * @return self
     */
    public function ignoresModel()
    {
        return $this->eloquentConfig([
            'ignoresModel' => true
        ]);
    }

    /**
     * Has a value but it is not persisted in DB.
     *
     * @return self
     */
    public function doesNotFill()
    {
        return $this->eloquentConfig([
            'doesNotFill' => true
        ]);
    }

    /**
     * Gets or sets the eloquent configuration of the field.
     *
     * @param  string|array $data  Array to set, string to retrieve the key
     *
     * @return  self
     */
    public function eloquentConfig($data)
    {
        if(is_array($data)){
            $this->_kompo('eloquent', $data);
            return $this;
        }else{
            return $this->_kompo('eloquent')[$data];
        }
    }

}