<?php

namespace App\Queue;

use App\Converter\HubspotPayloadConverter;
use App\Entity\HubspotPayload;
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
     * @var HubspotPayloadConverter
     */
    private $hubspotPayloadConverter;

    /**
     * @param PheanstalkProxy $pheanstalk
     * @param string $tubeName
     * @param HubspotManager $hubspotManager
     * @param WickerReportManager $wickerReportManager
     * @param EntityManagerInterface $em
     * @param HubspotHelper $hubspotHelper
     * @param HubspotPayloadConverter $hubspotPayloadConverter
     */
    public function __construct(
        PheanstalkProxy $pheanstalk,
        string $tubeName,
        HubspotManager $hubspotManager,
        WickerReportManager $wickerReportManager,
        EntityManagerInterface $em,
        HubspotHelper $hubspotHelper,
        HubspotPayloadConverter $hubspotPayloadConverter
    ) {

        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $tubeName;
        $this->hubspotManager = $hubspotManager;
        $this->wickerReportManager = $wickerReportManager;
        $this->em = $em;
        $this->hubspotHelper = $hubspotHelper;
        $this->hubspotPayloadConverter = $hubspotPayloadConverter;
    }

    /**
     * @param HubspotToken $hubspotToken
     */
    public function enqueue(HubspotToken $hubspotToken)
    {
        $hubspotPayload = new HubspotPayload(
            $hubspotToken
        );
        $this->pheanstalk
            ->useTube($this->tubeName)
            ->put(
                $this->hubspotPayloadConverter->toString($hubspotPayload)
            )
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
            $this->serveTokenPayload(
                $this->hubspotPayloadConverter->toPayload($job->getData())
            );
            $this->pheanstalk->delete($job);
        }
    }

    /**
     * @param HubspotPayload $hubspotPayload
     */
    public function serveTokenPayload(HubspotPayload $hubspotPayload): void
    {
        $wickedReportContacts = $this->hubspotManager->fetchContacts($hubspotPayload);

        if (
            $wickedReportContacts === null ||
            (
                $wickedReportContacts !== null &&
                count($wickedReportContacts->getContacts()) === 0
            )
        ) {
            $this->em->persist($hubspotPayload->getHubspotToken());
            $this->em->flush();

            return;
        }

        if (
            !$this->wickerReportManager->storeContacts($wickedReportContacts)
        ) {
            return;
        }

        if (!$wickedReportContacts->getHasMore()) {
            if (
                $hubspotPayload->getOriginalVidOffset() !== null &&
                $hubspotPayload->getOriginalTimeOffset() !== null
            ) {
                $token = $hubspotPayload
                    ->getHubspotToken()
                    ->setVidOffset($hubspotPayload->getOriginalVidOffset())
                    ->setTimeOffset($hubspotPayload->getOriginalTimeOffset())
                ;
                $this->em->persist($token);
                $this->em->flush();
                $this->em->detach($token);
            }

            return;
        }

        $this->pheanstalk
            ->useTube($this->tubeName)
            ->put($this->hubspotPayloadConverter->toString($hubspotPayload))
        ;
    }
}
