<?php

namespace Kompo\Database;

abstract class QueryOperations
{    
    protected $query;

    protected $komposer;

	abstract public function handleFilter($field);

    abstract public function getPaginated();

    /**
     * Constructs a Kompo\Database\QueryOperations object
     *
     * @param  array $komponents
     * @return void
     */
    public function __construct($query, $komposer)
    {
    	$this->query = $query;

    	$this->komposer = $komposer;
    }

    public function getQuery()
    {
        return $this->query;
    }


}