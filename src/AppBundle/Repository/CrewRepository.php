<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Crew;
use Doctrine\ORM\EntityRepository;

class CrewRepository extends EntityRepository
{
    /**
     * @return Crew[]
     */
    public function findAllVisible() {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c
                FROM AppBundle:Crew c
                WHERE c.display = TRUE
                ORDER BY c.position ASC')
            ->getResult();
    }
}

