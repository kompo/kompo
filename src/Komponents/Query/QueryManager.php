<?php

namespace Kompo\Komponents\Query;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\ValidationManager;

class QueryManager
{
    /**
     * For ordering items.
     *
     * @param Kompo\Query $query
     *
     * @return Illuminate\...\Paginator
     */
    public static function orderItems($query)
    {
        AuthorizationGuard::mainGate($query, 'ordering');

        ValidationManager::validateRequest($query);

        QueryDisplayer::prepareQuery($query);

        return $query->query->orderItems();
    }
}
