<?php

namespace App\Entity;

class HubspotPayload
{
    /**
     * @var HubspotToken
     */
    private $hubspotToken;

    /**
     * @var int|null
     */
    private $originalVidOffset;

    /**
     * @var int|null
     */
    private $originalTimeOffset;

    /**
     * @var int|null
     */
    private $nowVidOffset;

    /**
     * @var int|null
     */
    private $nowTimeOffset;

    /**
     * @param HubspotToken $hubspotToken
     * @param int|null $originalVidOffset
     * @param int|null $originalTimeOffset
     * @param int|null $nowVidOffset
     * @param int|null $nowTimeOffset
     */
    public function __construct(
        HubspotToken $hubspotToken,
        int $originalVidOffset = null,
        int $originalTimeOffset = null,
        int $nowVidOffset = null,
        int $nowTimeOffset = null
    ) {
        $this->hubspotToken = $hubspotToken;
        $this->originalVidOffset = $originalVidOffset;
        $this->originalTimeOffset = $originalTimeOffset;
        $this->nowVidOffset = $nowVidOffset;
        $this->nowTimeOffset = $nowTimeOffset;
    }

    /**
     * @return HubspotToken
     */
    public function getHubspotToken(): HubspotToken
    {
        return $this->hubspotToken;
    }

    /**
     * @return int|null
     */
    public function getOriginalVidOffset(): ?int
    {
        return $this->originalVidOffset;
    }

    /**
     * @return int|null
     */
    public function getOriginalTimeOffset(): ?int
    {
        return $this->originalTimeOffset;
    }

    /**
     * @return int|null
     */
    public function getNowVidOffset(): ?int
    {
        return $this->nowVidOffset;
    }

    /**
     * @return int|null
     */
    public function getNowTimeOffset(): ?int
    {
        return $this->nowTimeOffset;
    }
}
