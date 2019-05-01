<?php

namespace App\Converter;

use App\Entity\HubspotPayload;
use App\Entity\HubspotToken;
use Doctrine\ORM\EntityManagerInterface;

class HubspotPayloadConverter
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @param HubspotPayload $hubspotPayload
     *
     * @return string
     */
    public function toString(HubspotPayload $hubspotPayload): string
    {
        return implode(':', [
            $hubspotPayload->getHubspotToken()->getId(),
            (string)$hubspotPayload->getOriginalVidOffset(),
            (string)$hubspotPayload->getOriginalTimeOffset(),
            (string)$hubspotPayload->getNowVidOffset(),
            (string)$hubspotPayload->getNowTimeOffset(),
        ]);
    }

    /**
     * @param string $hubspotPayloadString
     *
     * @return HubspotPayload
     */
    public function toPayload(string $hubspotPayloadString): HubspotPayload
    {
        $exploded = explode(':', $hubspotPayloadString);

        if (count($exploded) !== 5) {
            throw new \LogicException(sprintf('Wrong HubspotPayload string format detected, "%s".', $hubspotPayloadString));
        }

        list($id, $originalVid, $originalTime, $nowVid, $nowTime) = $exploded;
        $hubspotToken = $this->em->getRepository(HubspotToken::class)->find($id);

        if (!$hubspotToken instanceof HubspotToken) {
            throw new \LogicException(sprintf('HubsportToken with "%s" was not found in DB.', $id));
        }

        return new HubspotPayload(
            $hubspotToken,
            $originalVid,
            $originalTime,
            $nowVid,
            $nowTime
        );
    }
}
