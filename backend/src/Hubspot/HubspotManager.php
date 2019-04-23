<?php

namespace App\Hubspot;

use App\Adapters\HubspotToWickedReportAdapter;
use App\Entity\HubspotToken;
use App\WickedReports\WickedReportContacts;

class HubspotManager
{
    /**
     * @var HubspotProvider
     */
    private $hubspotProvider;

    /**
     * @var HubspotToWickedReportAdapter
     */
    private $hubspotToWickedReportAdapter;

    /**
     * @var HubspotHelper
     */
    private $hubspotHelper;

    /**
     * @param HubspotProvider $hubspotProvider
     * @param HubspotToWickedReportAdapter $hubspotToWickedReportAdapter
     * @param HubspotHelper $hubspotHelper
     */
    public function __construct(
        HubspotProvider $hubspotProvider,
        HubspotToWickedReportAdapter $hubspotToWickedReportAdapter,
        HubspotHelper $hubspotHelper
    ) {
        $this->hubspotProvider = $hubspotProvider;
        $this->hubspotToWickedReportAdapter = $hubspotToWickedReportAdapter;
        $this->hubspotHelper = $hubspotHelper;
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
     * @return WickedReportContacts
     */
    public function fetchContacts(HubspotToken $hubspotToken): ?WickedReportContacts
    {
        try {
            $contactsData = $this->hubspotProvider->fetchContacts($hubspotToken);
        } catch (\Exception $e) {
            return null;
        }

        $wickedReportContacts = (new WickedReportContacts())
            ->setHubspotToken($hubspotToken)
            ->setContacts($this->hubspotToWickedReportAdapter->adapt($contactsData['contacts']))
            ->setTimeOffset($contactsData['time-offset'])
            ->setHasMore((bool)$contactsData['has-more'])
        ;

        return $wickedReportContacts;
    }
}
