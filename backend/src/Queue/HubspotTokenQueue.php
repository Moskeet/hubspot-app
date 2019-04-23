<?php

namespace App\Queue;

use App\Entity\HubspotToken;
use App\Hubspot\HubspotManager;
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
     * @param PheanstalkProxy $pheanstalk
     * @param string $tubeName
     * @param HubspotManager $hubspotManager
     * @param WickerReportManager $wickerReportManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        PheanstalkProxy $pheanstalk,
        string $tubeName,
        HubspotManager $hubspotManager,
        WickerReportManager $wickerReportManager,
        EntityManagerInterface $em
    ) {

        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $tubeName;
        $this->hubspotManager = $hubspotManager;
        $this->wickerReportManager = $wickerReportManager;
        $this->em = $em;
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

        if (
            $wickedReportContacts === null ||
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
}
