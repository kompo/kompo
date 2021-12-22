<?php

namespace Kompo\Interactions\Actions;

use Kompo\Routing\RouteFinder;

trait KomponentActions
{
    /**
     * Refreshes the Komponent(s) by reloading a fresh new version from the backend.
     * If the argument is left blank, the Komponent will refresh itself. To target another Komponent, add a string or array of target Komponent ids.
     *
     * @param null|string|array $komponentIds The id of the Komponent(s). Keep blank if targeting self.
     * @param array|null        $payload     Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function refresh($komponentIds = null, $payload = null)
    {
        return $this->prepareAction('refreshKomponent', [
            'route'                 => RouteFinder::getKompoRoute('POST'),
            'kompoid'               => $komponentIds,
            'ajaxPayload'           => $payload,
        ]);
    }
}
