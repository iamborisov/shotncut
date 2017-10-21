<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Client;
use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{
    /**
     * @return Client[]
     */
    public function findAllVisible() {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c
                FROM AppBundle:Client c
                WHERE c.display = TRUE
                ORDER BY c.position ASC')
            ->getResult();
    }
}

