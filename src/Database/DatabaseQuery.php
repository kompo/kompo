<?php 

namespace Kompo\Database;

use Kompo\Database\QueryOperations;
use Kompo\Input;

class DatabaseQuery extends QueryOperations
{
    public function handleFilter($field)
    {
        $name = $field->name;
        $operator = $this->inferBestOperator($field);
        $value = request($field->name);

        $this->query = $this->applyWhere($this->query, $name, $operator, $value);
    }

    public function getPaginated()
    {
        return $this->query->paginate($this->catalog->perPage, ['*'], 'page', $this->catalog->currentPage());
    }

    protected function inferBestOperator($field)
    {
        return $field->data('filterOperator') ?: (
            ($field->multiple ?? false) ? 'IN' : ($field instanceOf Input ? 'LIKE' : '=')
        );
    }

    public function applyWhere($q, $name, $operator, $value)
    {
        if($operator == 'IN'){
            return $q->whereIn($name, $value);
        }elseif($operator == 'LIKE'){
            return $q->where($name, 'LIKE', '%'.$value.'%');
        }elseif($operator == 'STARTSWITH'){
            return $q->where($name, 'LIKE', $value.'%');
        }elseif($operator == 'ENDSWITH'){
            return $q->where($name, 'LIKE', '%'.$value);
        }elseif($operator == 'BETWEEN'){
            return $q->whereBetween($name, $value);
        }else{
            return $q->where($name, $operator, $value);
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
        $this->pagination = $this->query->paginate($this->catalog->perPage, ['*'], 'page', $this->catalog->currentPage());
    }

    public function reorderItems($order)
    {
        foreach($order as $v)
        {
            with(clone $this->query)->where($this->getKeyName(), $v['id'])->update([
                $this->catalog->orderable => $v['order']
            ]);
        }
    }


}