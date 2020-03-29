<?php

namespace Kompo\Database;

abstract class QueryOperations
{    
    protected $query;

    protected $catalog;

	abstract public function handleFilter($field);

    abstract public function getPaginated();

    /**
     * Constructs a Vuravel\Catalog\EloquentQuery object
     *
     * @param  array $components
     * @return void
     */
    public function __construct($query, $catalog)
    {
    	$this->query = $query;

    	$this->catalog = $catalog;
    }

    public function getQuery()
    {
        return $this->query;
    }


}