<?php

namespace Kompo\Interactions\Actions;

use Kompo\Komponents\Field;

trait RefreshCatalogActions
{
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