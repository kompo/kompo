<?php

namespace Kompo\Interactions\Actions;

use Kompo\Exceptions\FilterOperatorNotAllowedException;
use Kompo\Exceptions\NotFilterCapableException;
use Kompo\Elements\Field;
use Kompo\Routing\RouteFinder;

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
        'IN',
        'NULL',
    ];

    /**
     * Filters a Query `onChange` for a Field or `onInput` for a text Input field by default.
     * If these default triggers do not suit you, use another one, for example: <php>->onBlur->filter('>=')</php>.
     *
     * @param string|null $operator Pick one of these supported operators `=`, `>`, `<`, `>=`, `<=`, `LIKE`, `STARTSWITH`, `ENDSWITH`, `BETWEEN`, `IN`.<br>Or keep blank to fallback to the field's default operator.
     *
     * @return self
     */
    public function filter($operator = null)
    {
        $this->applyToElement(function ($el) use ($operator) {
            if (!$el instanceof Field) {
                throw new NotFilterCapableException(class_basename($el));
            }

            if (!in_array($operator, self::$allowedOperators)) {
                throw new FilterOperatorNotAllowedException($operator);
            }

            $el->config(['filterOperator' => $operator]);
        });

        return $this->browse(null, 1); //filtering works for it's own query only
    }

    /**
     * Triggers a sort event of the query. The parameter is a pipe separated string of column:direction. Example: updated_at:DESC|last_name|first_name:ASC.
     *
     * @param string|null $sortOrders If field, the value will determine the sort. If trigger (link or th), we need to add a pipe serapated string of column:direction for ordering.
     *
     * @return self
     */
    public function sort($sortOrders = '')
    {
        $this->applyToElement(function ($el) use ($sortOrders) {
            if ($el instanceof Field) {
                $el->ignoresModel()->doesNotFill();
            }

            $el->config(['sortsQuery' => $sortOrders ?: true]);
        });

        return $this->prepareAction('sortQuery');
    }

    /**
     * Reload the cards of one or many Query(ies). If $queryId is left blank, it will browse cards of it's parent query.
     * Otherwise, you may set a string or an array of query ids to refresh.
     *
     * @param array|string|null $queryId The target Query Id or
     * @param int|null          $page    The page
     *
     * @return self
     */
    public function browse($queryId = null, $page = null)
    {
        return $this->prepareAction('browseQuery', [
            'route'                 => RouteFinder::getKompoRoute('POST'),
            'page'                  => $page,
            'kompoid'               => $queryId,
        ]);
    }

    /* For tests */
    public static function getAllowedOperators()
    {
        return static::$allowedOperators;
    }

    /**
     * Add an element to a Query's list.
     *
     * @param string $queryId Target Query ID
     * @param mixed $element Element to add (rendered on response)
     * @param string|int $position 'append', 'prepend', or numeric index
     * @param mixed $itemId Optional item ID for later updates/removal
     */
    public function addToQuery($queryId, $element, $position = 'append', $itemId = null)
    {
        $rendered = $element;
        if (is_object($element) && method_exists($element, 'render')) {
            $rendered = $element->render();
        } elseif (is_object($element) && method_exists($element, 'toArray')) {
            $rendered = $element->toArray();
        }

        $queryIdJson = json_encode($queryId);
        $elementJson = json_encode($rendered);
        $positionJson = json_encode($position);
        $itemIdJson = json_encode($itemId);

        return $this->run("({ \$k }) => \$k.query({$queryIdJson}).add({$elementJson}, {$positionJson}, {$itemIdJson})");
    }

    /**
     * Prepend an element to a Query's list.
     *
     * @param string $queryId Target Query ID
     * @param mixed $element Element to prepend
     * @param mixed $itemId Optional item ID for later updates/removal
     */
    public function prependToQuery($queryId, $element, $itemId = null)
    {
        return $this->addToQuery($queryId, $element, 'prepend', $itemId);
    }

    /**
     * Remove an item from a Query by ID.
     *
     * @param string $queryId Target Query ID
     * @param mixed $itemId Item ID to remove
     */
    public function removeFromQuery($queryId, $itemId)
    {
        $queryIdJson = json_encode($queryId);
        $itemIdJson = json_encode($itemId);

        return $this->run("({ \$k }) => \$k.query({$queryIdJson}).remove({$itemIdJson})");
    }

    /**
     * Update an item in a Query.
     *
     * @param string $queryId Target Query ID
     * @param mixed $itemId Item ID to update
     * @param mixed $element New element
     */
    public function updateInQuery($queryId, $itemId, $element)
    {
        $rendered = $element;
        if (is_object($element) && method_exists($element, 'render')) {
            $rendered = $element->render();
        } elseif (is_object($element) && method_exists($element, 'toArray')) {
            $rendered = $element->toArray();
        }

        $queryIdJson = json_encode($queryId);
        $itemIdJson = json_encode($itemId);
        $elementJson = json_encode($rendered);

        return $this->run("({ \$k }) => \$k.query({$queryIdJson}).update({$itemIdJson}, {$elementJson})");
    }

    /**
     * Trigger hybrid filter on a Query.
     *
     * @param string $queryId Target Query ID
     * @param string|null $value Filter value (if null, uses current field value)
     */
    public function hybridFilter($queryId, $value = null)
    {
        $queryIdJson = json_encode($queryId);

        if ($value !== null) {
            $valueJson = json_encode($value);
            return $this->run("({ \$k }) => \$k.query({$queryIdJson}).hybridFilter({$valueJson})");
        }

        // If no value, get from current field
        return $this->run("({ \$k, value }) => \$k.query({$queryIdJson}).hybridFilter(value)");
    }

    /**
     * Refresh a Query.
     *
     * @param string $queryId Target Query ID
     */
    public function refreshQuery($queryId)
    {
        $queryIdJson = json_encode($queryId);
        return $this->run("({ \$k }) => \$k.query({$queryIdJson}).refresh()");
    }

    // ==========================================
    // RESPONSE DESTINATION ACTIONS (like inPanel but for Query)
    // ==========================================

    /**
     * Append server response to a Query.
     * Chain this after selfPost/selfGet to add the response element to a Query.
     * Example: ->selfPost('createItem')->appendInQuery('items-list')
     *
     * @param string $queryId Target Query ID
     * @param mixed $itemId Optional item ID for later updates/removal
     */
    public function appendInQuery($queryId, $itemId = null)
    {
        return $this->prepareAction('appendInQuery', [
            'queryId' => $queryId,
            'itemId' => $itemId,
            'position' => 'append',
        ]);
    }

    /**
     * Prepend server response to a Query.
     * Chain this after selfPost/selfGet to prepend the response element to a Query.
     * Example: ->selfPost('createItem')->prependInQuery('items-list')
     *
     * @param string $queryId Target Query ID
     * @param mixed $itemId Optional item ID for later updates/removal
     */
    public function prependInQuery($queryId, $itemId = null)
    {
        return $this->prepareAction('appendInQuery', [
            'queryId' => $queryId,
            'itemId' => $itemId,
            'position' => 'prepend',
        ]);
    }

    /**
     * Update an item in a Query with server response.
     * Chain this after selfPost/selfGet to replace an existing item.
     * Example: ->selfPost('updateItem')->updateInQueryResponse('items-list', $item->id)
     *
     * @param string $queryId Target Query ID
     * @param mixed $itemId Item ID to update
     */
    public function updateInQueryResponse($queryId, $itemId)
    {
        return $this->prepareAction('updateInQueryResponse', [
            'queryId' => $queryId,
            'itemId' => $itemId,
        ]);
    }
}
