<?php

namespace Kompo\Elements\Traits;

trait HybridFiltering
{
    /**
     * Enable hybrid filtering on this input field.
     * Shows instant JS filtered results + loading bar, then syncs with server.
     *
     * @param string|null $queryId Target Query ID (optional - defaults to parent Query)
     * @param string $attribute The data attribute to filter on (default: 'data-filter')
     * @param int $debounce Debounce ms for server request (default: 300)
     *
     * @return self
     */
    public function hybridFilter($queryId = null, $attribute = 'data-filter', $debounce = 300)
    {
        return $this->config([
            'hybridFilter' => [
                'queryId' => $queryId, // null = use parent Query's kompoid
                'attribute' => $attribute,
                'debounce' => $debounce,
                'mode' => 'hybrid',
            ]
        ]);
    }

    /**
     * Enable server-only filtering (no instant JS).
     * Shows loading skeleton/overlay instead of items while waiting for server.
     * Use when instant filtering isn't possible (e.g., complex server-side filters).
     *
     * @param string|null|string[] $queryId Target Query ID (optional - defaults to parent Query)
     * @param int $debounce Debounce ms for server request (default: 300)
     *
     * @return self
     */
    public function serverFilter($queryId = null, $debounce = 300)
    {
        return $this->config([
            'hybridFilter' => [
                'queryId' => $queryId,
                'debounce' => $debounce,
                'mode' => 'server',
                'name' => $this->name,
            ]
        ]);
    }

    /**
     * Enable instant-only JS filtering (no server sync at all).
     * Useful for small datasets where server round-trip isn't needed.
     * WARNING: Results may become stale if server data changes.
     *
     * @param string|null $queryId Target Query ID (optional - defaults to parent Query)
     * @param string $attribute The data attribute to filter on
     *
     * @return self
     */
    public function jsInstantFilter($queryId = null, $attribute = 'data-filter')
    {
        return $this->config([
            'jsInstantFilter' => [
                'queryId' => $queryId,
                'attribute' => $attribute,
            ]
        ]);
    }

    /**
     * Mark this element as filterable with a specific value.
     * Used on Query cards to make them searchable by hybrid/instant filters.
     *
     * @param string|array $value The filterable value(s)
     *
     * @return self
     */
    public function filterable($value)
    {
        $filterValue = is_array($value) ? implode(' ', $value) : $value;

        return $this->attr(['data-filter' => $filterValue])
                    ->config(['filterValue' => $filterValue]);
    }
}
