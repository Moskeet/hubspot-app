<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HubspotTokenRepository")
 */
class HubspotToken
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128)
     *
     * @Assert\NotBlank
     */
    private $redirectUri;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotBlank
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $token;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $refreshToken;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $refreshDatetime;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $timeOffset;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setRedirectUri(?string $value): self
    {
        $this->redirectUri = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setCode(?string $value): self
    {
        $this->code = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setToken(?string $value): self
    {
        $this->token = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setRefreshToken(?string $value): self
    {
        $this->refreshToken = $value;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getRefreshDatetime(): ?\DateTime
    {
        return $this->refreshDatetime;
    }

    /**
     * @param \DateTime|null $value
     *
     * @return $this
     */
    public function setRefreshDatetime(?\DateTime $value): self
    {
        $this->refreshDatetime = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimeOffset(): ?string
    {
        return $this->timeOffset;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setTimeOffset(?string $value): self
    {
        $this->timeOffset = $value;

        return $this;
    }
}
