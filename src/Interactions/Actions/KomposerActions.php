<?php

namespace Kompo\Interactions\Actions;

use Kompo\Routing\RouteFinder;

trait KomposerActions
{
    /**
     * Refreshes the Komposer(s) by reloading a fresh new version from the backend.
     * If the argument is left blank, the Komposer will refresh itself. To target another Komposer, add a string or array of target Komposer ids.
     *
     * @param null|string|array $komposerIds The id of the Komposer(s). Keep blank if targeting self.
     * @param array|null $payload Additional custom data to add to the request (optional).
     *
     * @return self
     */
    public function refresh($komposerIds = null, $payload = null)
    {
        return $this->prepareAction('refreshKomposer', [
            'route'                 => RouteFinder::getKompoRoute('POST'),
            'kompoid'               => $komposerIds,
            'ajaxPayload'           => $payload,
        ]);
    }
}
