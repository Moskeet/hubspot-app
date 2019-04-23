<?php

namespace App\Adapters;

use App\Hubspot\HubspotHelper;
use App\WickedReports\WickedReportContactData;

class HubspotToWickedReportAdapter
{
    /**
     * @var HubspotHelper
     */
    private $hubspotHelper;

    /**
     * @param HubspotHelper $hubspotHelper
     */
    public function __construct(
        HubspotHelper $hubspotHelper
    ) {
        $this->hubspotHelper = $hubspotHelper;
    }

    /**
     * @param array $hubspotContacts
     *
     * @return array
     */
    public function adapt(array $hubspotContacts): array
    {
        return array_map(function($element) {
            return (new WickedReportContactData())
                ->setSystem(WickedReportContactData::SYSTEM_HUBSPOT)
                ->setSourceId($element['portal-id'])
                ->setCreateDate($this->extractCreateDate($element))
                ->setEmail($this->extractEmail($element))
                ->setFirstName($this->extractFirstName($element))
                ->setLastName($this->extractLastName($element))
            ;
        }, $hubspotContacts);
    }

    /**
     * @param array $element
     *
     * @return \DateTime
     */
    private function extractCreateDate(array $element): \DateTime
    {
        return $this->hubspotHelper->convertTimestampToDateTime($element['addedAt']);
    }

    /**
     * @param array $element
     *
     * @return string|null
     */
    private function extractEmail(array $element): ?string
    {
        foreach ($element['identity-profiles'] as $profile) {
            if (!isset($profile['identities'])) {
                continue;
            }

            foreach ($profile['identities'] as $identity) {
                if (
                    $identity['type'] === 'EMAIL' &&
                    !empty($identity['value'])
                ) {
                    return $identity['value'];
                }
            }
        }

        return null;
    }

    /**
     * @param array $element
     *
     * @return string|null
     */
    private function extractFirstName(array $element): ?string
    {
        return $element['properties']['firstname']['value'] ?? null;
    }

    /**
     * @param array $element
     *
     * @return string|null
     */
    private function extractLastName(array $element): ?string
    {
        return $element['properties']['lastname']['value'] ?? null;
    }
}
