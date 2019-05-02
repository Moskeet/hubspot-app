<?php

namespace App\Hubspot;

use App\Adapters\HubspotToWickedReportAdapter;
use App\Entity\HubspotPayload;
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
     * @param HubspotPayload $hubspotPayload
     *
     * @return WickedReportContacts
     */
    public function fetchContacts(HubspotPayload $hubspotPayload): ?WickedReportContacts
    {
        $vidOffset = $hubspotPayload->getNowVidOffset() ?? $hubspotPayload->getOriginalVidOffset();
        $timeOffset = $hubspotPayload->getNowTimeOffset() ?? $hubspotPayload->getOriginalTimeOffset();
        $hubspotToken = $hubspotPayload->getHubspotToken();

        try {
            $contactsData = $this->hubspotProvider->fetchContacts(
                $hubspotToken->getToken(),
                $vidOffset,
                $timeOffset
            );
        } catch (\Exception $e) {
            return null;
        }

        $contacts = $this->filterContactsList($hubspotToken, $contactsData['contacts']);

        if (!count($contacts)) {
            return (new WickedReportContacts())
                ->setHubspotPayload($hubspotPayload)
                ->setContacts([])
                ->setHasMore(false)
            ;
        }

        if (
            $hubspotPayload->getOriginalVidOffset() === null &&
            $hubspotPayload->getOriginalTimeOffset() === null
        ) {
            $firstContact = $contacts[0];
            $hubspotPayload
                ->setOriginalVidOffset($firstContact['vid'])
                ->setOriginalTimeOffset($firstContact['addedAt'])
            ;
        }

        $hubspotPayload
            ->setNowVidOffset($contactsData['vid-offset'])
            ->setNowTimeOffset($contactsData['time-offset'])
        ;
        $wickedReportContacts = (new WickedReportContacts())
            ->setHubspotPayload($hubspotPayload)
            ->setContacts($this->hubspotToWickedReportAdapter->adapt($contacts))
            ->setHasMore((bool)$contactsData['has-more'])
        ;

        return $wickedReportContacts;
    }

    /**
     * @param HubspotToken $hubspotToken
     * @param array $list
     *
     * @return array
     */
    private function filterContactsList(HubspotToken $hubspotToken, array $list): array
    {
        if ($hubspotToken->getVidOffset() === null && $hubspotToken->getTimeOffset() === null) {
            return $list;
        }

        $filtered = [];

        foreach ($list as $element) {
            if (
                (int)$hubspotToken->getTimeOffset() < (int)$element['addedAt'] ||
                (int)$hubspotToken->getVidOffset() === (int)$element['vid']
            ) {
                break;
            }

            $filtered[] = $element;
        }

        return $filtered;
    }
}
