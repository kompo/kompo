<?php 

namespace Kompo\Komponents\Traits;

trait FiltersCatalog {

    /**
     * Performs a 500ms debounced request to filter a Catalog when the user inputs in a Field. 
     * - To filter by an attribute, the field name should be the attribute that will be filtered. 
     * - For a relationship, you may chain a dot-separated string to filter against a nested relationship.
     *
     * @param string|null $operator A supported operator '=','>','<','>=','<=','LIKE','STARTSWITH','ENDSWITH','BETWEEN','IN'
     *
     * @return     self
     */
    public function onInputFilter($operator = null)
    {
        return $this->onInput( function($e) use ($operator) {
            $e->filter($operator)
              ->debounce();
        });
    }

}