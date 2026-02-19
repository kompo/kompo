<?php

namespace Kompo\Elements;

/**
 * SelectionBar - Odoo-style bulk selection bar for Query/Table components.
 *
 * Provides "Select All X" functionality for paginated lists, allowing users
 * to select all items across all pages without loading all IDs into memory.
 *
 * Usage:
 * ```php
 * _SelectionBar('users-query')
 *     ->actionsComponent(
 *         _Button('Actions')->dropdown([
 *             _Link('Delete Selected')->selfPost('bulkDelete'),
 *             _Link('Export')->selfPost('bulkExport'),
 *         ])
 *     )
 * ```
 */
class SelectionBar extends Element
{
    /**
     * The Vue component name.
     *
     * @var string
     */
    public $vueComponent = 'SelectionBar';

    /**
     * The query ID this bar is attached to.
     *
     * @var string|null
     */
    protected $queryId;

    /**
     * Create a selection bar for a Query/Table.
     *
     * @param string|null $queryId The Query ID to attach to (optional, uses parent kompoid if not specified)
     */
    public function __construct($queryId = null)
    {
        parent::__construct();

        $this->queryId = $queryId;

        if ($queryId) {
            $this->config(['queryId' => $queryId]);
        }
    }

    /**
     * Set the actions component (button, dropdown, etc.)
     * This component will be displayed in the actions area of the selection bar.
     *
     * @param Element $component The component to show in the actions area
     * @return self
     */
    public function actionsComponent($component)
    {
        $rendered = $component;

        if (is_object($component) && method_exists($component, 'render')) {
            $rendered = $component->render();
        } elseif (is_object($component) && method_exists($component, 'toArray')) {
            $rendered = $component->toArray();
        }

        return $this->config(['actionsComponent' => $rendered]);
    }

    /**
     * Add custom CSS class to the selection bar.
     *
     * @param string $class
     * @return self
     */
    public function barClass($class)
    {
        return $this->config(['barClass' => $class]);
    }

    /**
     * Static constructor for cleaner syntax.
     *
     * @param string|null $queryId
     * @return static
     */
    public static function forQuery($queryId = null)
    {
        return new static($queryId);
    }
}
