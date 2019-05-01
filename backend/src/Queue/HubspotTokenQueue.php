<?php

namespace App\Queue;

use App\Entity\HubspotToken;
use App\Hubspot\HubspotHelper;
use App\Hubspot\HubspotManager;
use App\WickedReports\WickedReportContactData;
use App\WickedReports\WickerReportManager;
use Doctrine\ORM\EntityManagerInterface;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;

class HubspotTokenQueue
{
    /**
     * @var PheanstalkProxy
     */
    private $pheanstalk;

    /**
     * @var string
     */
    private $tubeName;

    /**
     * @var HubspotManager
     */
    private $hubspotManager;

    /**
     * @var WickerReportManager
     */
    private $wickerReportManager;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var HubspotHelper
     */
    private $hubspotHelper;

    /**
     * @param PheanstalkProxy $pheanstalk
     * @param string $tubeName
     * @param HubspotManager $hubspotManager
     * @param WickerReportManager $wickerReportManager
     * @param EntityManagerInterface $em
     * @param HubspotHelper $hubspotHelper
     */
    public function __construct(
        PheanstalkProxy $pheanstalk,
        string $tubeName,
        HubspotManager $hubspotManager,
        WickerReportManager $wickerReportManager,
        EntityManagerInterface $em,
        HubspotHelper $hubspotHelper
    ) {

        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $tubeName;
        $this->hubspotManager = $hubspotManager;
        $this->wickerReportManager = $wickerReportManager;
        $this->em = $em;
        $this->hubspotHelper = $hubspotHelper;
    }

    /**
     * @param HubspotToken $hubspotToken
     */
    public function enqueue(HubspotToken $hubspotToken)
    {
        $this->pheanstalk
            ->useTube($this->tubeName)
            ->put($hubspotToken->getId())
        ;
    }

    public function listen()
    {
        ini_set('default_socket_timeout', 86400);

        while (true) {
            $job = $this->pheanstalk
                ->watch($this->tubeName)
                ->ignore('default')
                ->reserve()
            ;
            $hubspotToken = $this
                ->em
                ->getRepository(HubspotToken::class)
                ->find(
                    $job->getData()
                )
            ;
            $this->serveToken($hubspotToken);
            $this->pheanstalk->delete($job);
        }
    }

    /**
     * @param HubspotToken $hubspotToken
     */
    public function serveToken(HubspotToken $hubspotToken): void
    {
        $wickedReportContacts = $this->hubspotManager->fetchContacts($hubspotToken);

        if ($wickedReportContacts === null) {
            return;
        }

        $wickedReportContacts->setContacts(
            $this->filterContacts(
                $wickedReportContacts->getContacts(),
                $this->hubspotHelper->convertTimestampToDateTime($hubspotToken->getTimeOffset())
            )
        );

        if (
            count($wickedReportContacts->getContacts()) === 0 ||
            !$this->wickerReportManager->storeContacts($wickedReportContacts)
        ) {
            return;
        }

        $hubspotToken->setTimeOffset($wickedReportContacts->getTimeOffset());
        $this->em->persist($hubspotToken);
        $this->em->flush();

        if (!$wickedReportContacts->getHasMore()) {
            return;
        }

        $this->pheanstalk
            ->useTube($this->tubeName)
            ->put($hubspotToken->getId())
        ;
    }

    /**
     * @param array|WickedReportContactData[] $contacts
     * @param \DateTime|null $tokenDateTime
     *
     * @return array|WickedReportContactData[]
     */
    private function filterContacts(array $contacts, ?\DateTime $tokenDateTime): array
    {
        if ($tokenDateTime === null) {
            return $contacts;
        }

        return array_map($contacts, function($element) use ($tokenDateTime) {
            /** @var WickedReportContactData $element */
            return $element->getCreateDate() > $tokenDateTime;
        });
    }
}
