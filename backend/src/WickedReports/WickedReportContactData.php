<?php

namespace App\WickedReports;

class WickedReportContactData
{
    /**
     * @var string "SourceSystem": varchar(255), //REQUIRED//; name of the system where contacts coming from, may be any chosen string, must be used everywhere after, helps to identify source of contacts, (e.g. ‘ActiveCampaign’, ‘Shopify’, ‘MailChimp’)
     */
    private $system;

    /**
     * @var string "SourceID": varchar(500), //REQUIRED//; Unique ID of the contact in the original system
     */
    private $sourceId;

    /**
     * @var \DateTime "CreateDate": datetime, should be UTC Timezone //REQUIRED//; date & time when contact was created in the original system, format: "YYYY-MM-DD HH:MM:SS" (Reminder, date must be in UTC TIME)
     */
    private $createDate;

    /**
     * @var string "Email": varchar(500) // REQUIRED//; email address of the contact
     */
    private $email;

    /**
     * @var string|null "FirstName": varchar(500)
     */
    private $firstName;

    /**
     * @var string|null "LastName": varchar(500)
     */
    private $lastName;

    /**
     * @var string|null "City": varchar(500) // not required, but needed for GEO and Predictive Behavior reports
     */
    private $city;

    /**
     * @var string|null "State": varchar(500) //not required, but needed for GEO and Predictive Behavior reports
     */
    private $state;

    /**
     * @var string|null "Country": varchar(500) //not required, but needed for GEO and Predictive Behavior reports
     */
    private $country;

    /**
     * @var string|null "IP_Address": varchar(500) // IP Address of the contact when they provided email address
     */
    private $ipAddress;

    /**
     * @return string
     */
    public function getSystem(): string
    {
        return $this->system;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setSystem(string $value): self
    {
        $this->system = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setSourceId(string $value): self
    {
        $this->sourceId = $value;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate(): \DateTime
    {
        return $this->createDate;
    }

    /**
     * @param \DateTime $value
     *
     * @return $this
     */
    public function setCreateDate(\DateTime $value): self
    {
        $this->createDate = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setEmail(string $value): self
    {
        $this->email = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setFirstName(?string $value): self
    {
        $this->firstName = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setLastName(?string $value): self
    {
        $this->lastName = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setCity(?string $value): self
    {
        $this->city = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setState(?string $value): self
    {
        $this->state = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setCountry(?string $value): self
    {
        $this->country = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    /**
     * @param string|null $value
     *
     * @return $this
     */
    public function setIpAddress(?string $value): self
    {
        $this->ipAddress = $value;

        return $this;
    }
}
