<?php

namespace App\WickedReports;

use App\Entity\HubspotToken;

class WickedReportContacts
{
    /**
     * @var HubspotToken
     */
    private $hubspotToken;

    /**
     * @var array
     */
    private $contacts;

    /**
     * @var string
     */
    private $timeOffset;

    /**
     * @var bool
     */
    private $hasMore;

    /**
     * @return HubspotToken
     */
    public function getHubspotToken(): HubspotToken
    {
        return $this->hubspotToken;
    }

    /**
     * @param HubspotToken $value
     *
     * @return $this
     */
    public function setHubspotToken(HubspotToken $value): self
    {
        $this->hubspotToken = $value;

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
     * @return string
     */
    public function getTimeOffset(): string
    {
        return $this->timeOffset;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setTimeOffset(string $value): self
    {
        $this->timeOffset = $value;

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
