<?php

namespace App\Hubspot;

use App\Entity\HubspotToken;

class HubspotManager
{
    /**
     * @var HubspotProvider
     */
    private $hubspotProvider;

    /**
     * @param HubspotProvider $hubspotProvider
     */
    public function __construct(
        HubspotProvider $hubspotProvider
    ) {
        $this->hubspotProvider = $hubspotProvider;
    }

    /**
     * @param HubspotToken $hubspotToken
     *
     * @return HubspotToken|null
     */
    public function getTokenByCode(HubspotToken $hubspotToken): ?HubspotToken
    {
        try {
            $this->hubspotProvider->getTokenByCode($hubspotToken);
        } catch (\Exception $e) {
            return null;
        }

        if ($hubspotToken->getToken() !== null) {
            return $hubspotToken;
        }

        return null;
    }

    /**
     * @param HubspotToken $hubspotToken
     *
     * @return bool
     */
    public function refreshToken(HubspotToken $hubspotToken): bool
    {
        try {
            $this->hubspotProvider->refreshToken($hubspotToken);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param HubspotToken $hubspotToken
     *
     * @return bool
     */
    public function fetchContacts(HubspotToken $hubspotToken): bool
    {
        try {
            $contacts = $this->hubspotProvider->fetchContacts($hubspotToken);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
