<?php

namespace Kompo\Database;

use Kompo\Core\RequestData;
use Kompo\Input;

abstract class QueryOperations
{
    protected $query;

    protected $komposer;

    abstract public function handleFilter($field);

    abstract public function getPaginated();

    abstract public function orderItems();

    /**
     * Constructs a Kompo\Database\QueryOperations object.
     *
     * @param array $komponents
     *
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

    protected function inferBestOperator($field)
    {
        return $field->config('filterOperator') ?: (
            (property_exists($field, 'multiple') && $field->multiple) ? 'IN' : ($field instanceof Input ? 'LIKE' : '=')
        );
    }

    protected function getFilterValueFromRequest($name)
    {
        return RequestData::get($name);
    }
}
