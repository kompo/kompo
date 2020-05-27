<?php 

namespace Kompo\Database;

use Kompo\Database\QueryOperations;

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
        return $this->query->paginate($this->komposer->perPage, ['*'], 'page', $this->komposer->currentPage());
    }

    public function applyWhere($q, $name, $operator, $value, $table = null)
    {
        $columnName = ($table? ($table.'.') : '').$name;
        
        if($operator == 'IN'){
            return $q->whereIn($columnName, $value);
        }elseif($operator == 'LIKE'){
            return $q->where($columnName, 'LIKE', '%'.$value.'%');
        }elseif($operator == 'STARTSWITH'){
            return $q->where($columnName, 'LIKE', $value.'%');
        }elseif($operator == 'ENDSWITH'){
            return $q->where($columnName, 'LIKE', '%'.$value);
        }elseif($operator == 'BETWEEN'){
            return $q->whereBetween($columnName, $value);
        }else{
            return $q->where($columnName, $operator, $value);
        }
    }

    public function handleSort($sort)
    {
        //clearing orderBy from query, should give the user the ability to do so or not
        $temp = $this->query->getQuery();
        $temp->orders = [];
        $this->query->setQuery($temp);
        foreach(explode('|', $sort) as $colDir)
        {
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

    public function executePagination()
    {
        $this->pagination = $this->query->paginate($this->komposer->perPage, ['*'], 'page', $this->komposer->currentPage());
    }

    public function reorderItems($order)
    {
        foreach($order as $v)
        {
            with(clone $this->query)->where($this->getKeyName(), $v['id'])->update([
                $this->query->orderable => $v['order']
            ]);
        }
    }


}