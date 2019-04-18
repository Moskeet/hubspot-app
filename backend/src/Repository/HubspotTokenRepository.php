<?php

namespace App\Repository;

use App\Entity\HubspotToken;
use Doctrine\ORM\EntityRepository;

class HubspotTokenRepository extends EntityRepository
{
    /**
     * @return HubspotToken|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLatestToken(): ?HubspotToken
    {
        $qb = $this->createQueryBuilder('ht');
        $qb
            ->setFirstResult(0)
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param \DateTime $marker
     *
     * @return HubspotToken[]
     */
    public function getAllAfterMarker(\DateTime $marker)
    {
        $qb = $this->createQueryBuilder('ht');
        $qb
            ->where('ht.refreshDatetime < :marker')
            ->setParameter(':marker', $marker->format("Y-m-d H:i:s"))
        ;

        return $qb->getQuery()->execute();
    }
}
