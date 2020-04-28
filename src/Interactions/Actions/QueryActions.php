<?php

namespace Kompo\Interactions\Actions;

use Kompo\Exceptions\FilterOperatorNotAllowedException;
use Kompo\Exceptions\NotFilterCapableException;
use Kompo\Komponents\Field;

trait QueryActions
{
    protected static $allowedOperators = [
        '',
        '=',
        '>',
        '<',
        '>=',
        '<=',
        'LIKE',
        'STARTSWITH',
        'ENDSWITH',
        'BETWEEN',
        'IN'
    ];

    /**
     * Filters a Query onChange for a Field. 
     * - To filter by an attribute, the field name should be the attribute that will be filtered. 
     * - For a relationship, you may chain a dot-separated string to filter against a nested relationship.
     *
     * @param string|null $operator A supported operator =, >, <, >=, <=, LIKE, STARTSWITH, ENDSWITH, BETWEEN, IN
     *
     * @return     self
     */
    public function filter($operator = null)
    {
        $this->applyToElement(function($element) use($operator) {
            if(!$element instanceOf Field)
                throw new NotFilterCapableException(class_basename($element));

            if(!in_array($operator, self::$allowedOperators))
                throw new FilterOperatorNotAllowedException($operator);

            $element->data([ 'filterOperator' => $operator  ]);
        });

        return $this->refresh(null, 1); //filtering works for it's own query only
    }

    /**
     * Triggers a sort event of the query. The parameter is a pipe separated string of column:direction. Example: updated_at:DESC|last_name|first_name:ASC.
     *
     * @param string|null  $sortOrders  If field, the value will determine the sort. If trigger (link or th), we need to add a pipe serapated string of column:direction for ordering.
     *
     * @return self  
     */
    public function sort($sortOrders = '')
    {
        $this->applyToElement(function($element) use($sortOrders) {
            if($element instanceOf Field)
                $element->ignoresModel()->doesNotFill();

            $element->data([ 'sortsQuery' => $sortOrders ?: true ]);
        });

        return $this->prepareAction('sortQuery');
    }

    public function refresh($queryId = null, $page = null)
    {
        return $this->prepareAction('refreshQuery', [
            'page' => $page,
            'vuravelid' => $queryId
        ]);
    }



    /* For tests */
    public static function getAllowedOperators()
    {
        return static::$allowedOperators;
    }
    
}