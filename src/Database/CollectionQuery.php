<?php

namespace Kompo\Database;

use Illuminate\Pagination\LengthAwarePaginator;
use Kompo\Core\Util;
use Kompo\Exceptions\NotOrderableQueryException;

class CollectionQuery extends QueryOperations
{
    /**
     * Constructs a Kompo\Database\CollectionQuery object.
     *
     * @param array $elements
     *
     * @return void
     */
    public function __construct($query, $komponent)
    {
        parent::__construct(Util::collect($query), $komponent);
    }

    public function orderItems()
    {
        throw new NotOrderableQueryException();
    }

    public function handleFilter($field)
    {
        $name = $field->name;
        $operator = $this->inferBestOperator($field);
        $value = $this->getFilterValueFromRequest($name);

        if ($operator == 'IN') {
            $this->query = $this->query->filter(function ($v, $k) use ($name, $value) {
                return $this->compareIn($v, $value, $name);
            });
        } elseif ($operator == 'LIKE') {
            $this->query = $this->query->filter(function ($v, $k) use ($name, $value) {
                return $this->compareLike($v, $value, $name);
            });
        } elseif ($operator == 'STARTSWITH') {
            $this->query = $this->query->filter(function ($v, $k) use ($name, $value) {
                return $this->compareStart($v, $value, $name);
            });
        } elseif ($operator == 'ENDSWITH') {
            $this->query = $this->query->filter(function ($v, $k) use ($name, $value) {
                return $this->compareEnd($v, $value, $name);
            });
        } elseif ($operator == 'BETWEEN') {
            $this->query = $this->query->filter(function ($v, $k) use ($name, $value) {
                return $this->compareBetween($v, $value, $name);
            });
        } elseif ($operator == 'NULL') {
            $this->query = $this->query->filter(function ($v, $k) use ($name, $value) {
                return $this->compareNull($v, $value, $name);
            });
        } else {
            $this->query = $this->query->filter(function ($v, $k) use ($operator, $name, $value) {
                return $this->compareOperator($v, $value, $operator, $name);
            });
        }
    }

    public function applyThFilter($name, $operator, $value)
    {
        if ($operator == 'IN') {
            $this->query = $this->query->filter(fn($v) => $this->compareIn($v, $value, $name));
        } elseif ($operator == 'LIKE') {
            $this->query = $this->query->filter(fn($v) => $this->compareLike($v, $value, $name));
        } else {
            $this->query = $this->query->filter(fn($v) => $this->compareOperator($v, $value, $operator, $name));
        }
    }

    public function getPaginated()
    {
        $slice = $this->query->slice(($this->komponent->currentPage() - 1) * $this->komponent->perPage, $this->komponent->perPage)->values();

        return new LengthAwarePaginator($slice, $this->query->count(), $this->komponent->perPage, $this->komponent->currentPage());
    }

    public function handleSort($sort)
    {
        $reversedArray = array_reverse(explode('|', $sort));
        
        foreach ($reversedArray as $colDir) {

            $colDir = explode(':', $colDir);

            $this->query = (count($colDir) == 2 && $colDir[1] == 'DESC' )? 
                $this->query->sortByDesc($colDir[0]) : 
                $this->query->sortBy($colDir[0]);
        }
    }

    /******************************************
    ******* PROTECTED COMPARISONS *************
    ******************************************/

    protected function compareOperator($comparing, $compareTo, $operator, $name = null)
    {
        $compareValue = $this->getComparingValue($comparing, $compareTo, $name);

        switch ($operator) {
            case '=':
                return $compareValue == $compareTo;
            case '>=':
                return $compareValue >= $compareTo;
            case '<=':
                return $compareValue <= $compareTo;
            case '>':
                return $compareValue > $compareTo;
            case '<':
                return $compareValue < $compareTo;
        }
    }

    protected function compareIn($comparing, $compareTo, $name = null)
    {
        $compareValue = $this->getComparingValue($comparing, $compareTo, $name);

        return in_array($compareValue, $compareTo);
    }

    protected function compareBetween($comparing, $compareTo, $name = null)
    {
        $compareValue = $this->getComparingValue($comparing, $compareTo, $name);

        return ($compareValue >= $compareTo[0]) && ($compareValue <= $compareTo[1]);
    }

    protected function compareLike($comparing, $compareTo, $name = null)
    {
        $compareValue = $this->getComparingValue($comparing, $compareTo, $name);

        return strpos($compareValue, $compareTo) > -1;
    }

    protected function compareStart($comparing, $compareTo, $name = null)
    {
        $compareValue = $this->getComparingValue($comparing, $compareTo, $name);

        return strpos($compareValue, $compareTo) === 0;
    }

    protected function compareEnd($comparing, $compareTo, $name = null)
    {
        $compareValue = $this->getComparingValue($comparing, $compareTo, $name);

        return substr($compareValue, -strlen($compareTo)) === $compareTo;
    }

    protected function compareNull($comparing, $compareTo, $name = null)
    {
        $compareValue = $this->getComparingValue($comparing, $compareTo, $name);

        return is_null($compareValue);
    }

    protected function getComparingValue($comparing, $compareTo, $name = null)
    {
        return is_array($comparing) ? ($comparing[$name] ?? null) :
            (is_object($comparing) ? ($comparing->{$name} ?? null) : $comparing);
    }
}
