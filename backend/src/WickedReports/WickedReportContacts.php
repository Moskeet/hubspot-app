<?php

namespace App\WickedReports;

use App\Entity\HubspotPayload;

class WickedReportContacts
{
    /**
     * @var HubspotPayload
     */
    private $hubspotPayload;

    /**
     * @var array
     */
    private $contacts;

    /**
     * @var bool
     */
    private $hasMore;

    /**
     * @return HubspotPayload
     */
    public function getHubspotPayload(): HubspotPayload
    {
        return $this->hubspotPayload;
    }

    /**
     * @param HubspotPayload $value
     *
     * @return $this
     */
    public function setHubspotPayload(HubspotPayload $value): self
    {
        $this->hubspotPayload = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * @param array $value
     *
     * @return $this
     */
    public function setContacts(array $value): self
    {
        $this->contacts = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHasMore(): bool
    {
        return $this->hasMore;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setHasMore(bool $value): self
    {
        $this->hasMore = $value;

        return $this;
    }
}
