<?php

declare(strict_types=1);

namespace Meetup\Repository;

use Doctrine\ORM\EntityRepository;
use DoctrineORMModule\Service\DoctrineObjectHydratorFactory;
use Meetup\Entity\Meetup;

final class MeetupRepository extends EntityRepository
{
    public function add($meetup) : void
    {
        $this->getEntityManager()->persist($meetup);
        $this->getEntityManager()->flush($meetup);
    }

    public function createMeetup(string $name, string $description, $startAt, $endStart) : Meetup
    {
        return new Meetup($name, $description, $startAt, $endStart);
    }

    public function remove($meetup) : void
    {
        $this->getEntityManager()->remove($meetup);
        $this->getEntityManager()->flush($meetup);
    }

    public function updateMeetup($meetup) : void
    {
        $this->getEntityManager()->flush();
    }
}