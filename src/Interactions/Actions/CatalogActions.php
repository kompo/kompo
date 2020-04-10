<?php

namespace Kompo\Interactions\Actions;

use Kompo\Exceptions\FilteringOperatorNotAllowedException;
use Kompo\Exceptions\NotFilteringComponentException;
use Kompo\Komponents\Field;

trait CatalogActions
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
     * Filters a Catalog onChange for a Field. 
     * - To filter by an attribute, the field name should be the attribute that will be filtered. 
     * - For a relationship, you may chain a dot-separated string to filter against a nested relationship.
     *
     * @param string|null $operator A supported operator '=','>','<','>=','<=','LIKE','STARTSWITH','ENDSWITH','BETWEEN','IN'
     *
     * @return     self
     */
    public function filter($operator = null)
    {
        $this->applyToElement(function($element) use($operator) {
            if(!$element instanceOf Field)
                throw new NotFilteringComponentException(class_basename($element));

            if(!in_array($operator, self::$allowedOperators))
                throw new FilteringOperatorNotAllowedException($operator);

            $element->data([ 'filterOperator' => $operator  ]);
        });

        return $this->refreshCatalog(null, 1); //filtering works for it's own catalog only
    }

    /**
     * Triggers a sort event of the catalog. The parameter is a pipe separated string of column:direction. Example: updated_at:DESC|last_name|first_name:ASC.
     *
     * @param string|null  $sortOrders  If field, the value will determine the sort. If trigger (link or th), we need to add a pipe serapated string of column:direction for ordering.
     *
     * @return self  
     */
    public function sortCatalog($sortOrders = '')
    {
        $this->applyToElement(function($element) {
            if($element instanceOf Field)
                $element->ignoresModel()->doesNotFill();
        });

        return $this->prepareAction('sortCatalog', [
            'sortsCatalog' => $sortOrders ?: true
        ]);
    }

    public function refreshCatalog($catalogId = null, $page = null)
    {
        return $this->prepareAction('refreshCatalog', [
            'page' => $page,
            'vuravelid' => $catalogId
        ]);
    }
    
}