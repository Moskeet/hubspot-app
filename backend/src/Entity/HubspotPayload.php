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
     * @return int|null
     */
    public function getOriginalVidOffset(): ?int
    {
        return $this->originalVidOffset;
    }

    /**
     * @param int|null $value
     *
     * @return $this
     */
    public function setOriginalVidOffset(?int $value): self
    {
        $this->originalVidOffset = $value;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOriginalTimeOffset(): ?int
    {
        return $this->originalTimeOffset;
    }

    /**
     * @param int|null $value
     *
     * @return $this
     */
    public function setOriginalTimeOffset(?int $value): self
    {
        $this->originalTimeOffset = $value;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNowVidOffset(): ?int
    {
        return $this->nowVidOffset;
    }

    /**
     * @param int|null $value
     *
     * @return $this
     */
    public function setNowVidOffset(?int $value): self
    {
        $this->nowVidOffset = $value;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNowTimeOffset(): ?int
    {
        return $this->nowTimeOffset;
    }

    /**
     * @param int|null $value
     *
     * @return $this
     */
    public function setNowTimeOffset(?int $value): self
    {
        $this->nowTimeOffset = $value;

        return $this;
    }
}
