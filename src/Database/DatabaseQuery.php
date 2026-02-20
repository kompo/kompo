<?php

namespace Kompo\Database;

class DatabaseQuery extends QueryOperations
{
    public function handleFilter($field)
    {
        $name = $field->name;
        $operator = $this->inferBestOperator($field);
        $value = $this->getFilterValueFromRequest($name);

        $this->query = $this->applyWhere($this->query, $name, $operator, $value);
    }

    public function getPaginated()
    {
        return $this->query->paginate($this->komponent->perPage, ['*'], 'page', $this->komponent->currentPage());
    }

    public function applyThFilter($name, $operator, $value)
    {
        $this->query = $this->applyWhere($this->query, $name, $operator, $value);
    }

    public function applyWhere($q, $name, $operator, $value, $table = null)
    {
        if (is_array($value) && !in_array($operator, ['IN', 'BETWEEN'])) {
            return $q;
        }

        $columnName = ($table ? ($table.'.') : '').$name;

        if ($operator == 'IN') {
            return $q->whereIn($columnName, $value);
        } elseif ($operator == 'LIKE') {
            return $q->where($columnName, 'LIKE', '%'.$value.'%');
        } elseif ($operator == 'STARTSWITH') {
            return $q->where($columnName, 'LIKE', $value.'%');
        } elseif ($operator == 'ENDSWITH') {
            return $q->where($columnName, 'LIKE', '%'.$value);
        } elseif ($operator == 'BETWEEN') {
            return $q->whereBetween($columnName, $value);
        } elseif ($operator == 'NULL') {
            return $q->whereNull($columnName);
        } else {
            return $q->where($columnName, $operator, $value);
        }
    }

    public function orderItems()
    {
        \DB::transaction(function () {
            collect(request('order'))->each(function ($order) {
                $this->orderQuery()->where($this->modelKeyName(), $order['item_id'])->update([
                    $this->modelOrderColumn() => $order['item_order'],
                ]);
            });
        });
    }

    protected function orderQuery()
    {
        return $this->query;
    }

    protected function modelKeyName()
    {
        return 'id';
    }

    protected function modelOrderColumn()
    {
        return $this->komponent->orderable;
    }

    public function handleSort($sort)
    {
        //clearing orderBy from query, should give the user the ability to do so or not
        $temp = $this->query->getQuery();
        $temp->orders = [];
        $this->query->setQuery($temp);
        foreach (explode('|', $sort) as $colDir) {
            $this->sortBy($colDir);
        }
    }

    public function sortBy($sort)
    {
        $this->attributeSort($sort);
    }

    public function attributeSort($sort)
    {
        $sort = explode(':', $sort);

        $this->query->orderBy($sort[0], count($sort) == 2 ? $sort[1] : 'ASC');
    }
}
