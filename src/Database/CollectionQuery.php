<?php

namespace Kompo\Database;

use Kompo\Core\Util;
use Kompo\Database\QueryOperations;
use Illuminate\Pagination\LengthAwarePaginator;

class CollectionQuery extends QueryOperations
{
    /**
     * Constructs a Kompo\Database\CollectionQuery object
     *
     * @param  array $components
     * @return void
     */
    public function __construct($query, $komposer)
    {
        parent::__construct(Util::collect($query), $komposer);
    }

    public function handleFilter($field)
    {
        
    }

    public function getPaginated()
    {
        $slice = $this->query->slice(($this->komposer->currentPage() - 1)* $this->komposer->perPage, $this->komposer->perPage)->values();

        return new LengthAwarePaginator($slice, $this->query->count(), $this->komposer->perPage, $this->komposer->currentPage());
    }
    
}