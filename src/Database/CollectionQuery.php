<?php

namespace Kompo\Database;

use Kompo\Core\Util;
use Kompo\Database\QueryOperations;
use Illuminate\Pagination\LengthAwarePaginator;

class CollectionQuery extends QueryOperations
{
    /**
     * Constructs a Vuravel\Catalog\CollectionQuery object
     *
     * @param  array $components
     * @return void
     */
    public function __construct($query, $catalog)
    {
        parent::__construct(Util::collect($query), $catalog);
    }

    public function handleFilter($field)
    {
        
    }

    public function getPaginated()
    {
        $slice = $this->query->slice(($this->catalog->currentPage() - 1)* $this->catalog->perPage, $this->catalog->perPage)->values();

        return new LengthAwarePaginator($slice, $this->query->count(), $this->catalog->perPage, $this->catalog->currentPage());
    }
    
}