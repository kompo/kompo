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
     * Filters a Query `onChange` for a Field or `onInput` for a text Input field by default. 
     * If these default triggers do not suit you, use another one, for example: <php>->onBlur->filter('>=')</php>
     *
     * @param string|null $operator Pick one of these supported operators `=`, `>`, `<`, `>=`, `<=`, `LIKE`, `STARTSWITH`, `ENDSWITH`, `BETWEEN`, `IN`.<br>Or keep blank to fallback to the field's default operator.
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

        return $this->browse(null, 1); //filtering works for it's own query only
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


    /**
     * Reload the cards of one or many Query(ies). If $queryId is left blank, it will browse cards of it's parent query.
     * Otherwise, you may set a string or an array of query ids to refresh.
     *
     * @param string|null   $queryId  The target Query Id or
     * @param integer|null  $page     The page
     *
     * @return self
     */
    public function browse($queryId = null, $page = null)
    {
        return $this->prepareAction('browseQuery', [
            'page' => $page,
            'kompoid' => $queryId
        ]);
    }



    /* For tests */
    public static function getAllowedOperators()
    {
        return static::$allowedOperators;
    }
    
}