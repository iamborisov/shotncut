<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Gallery;
use Doctrine\ORM\EntityRepository;

class GalleryRepository extends EntityRepository
{
    /**
     * @return Gallery[]
     */
    public function findAllVisible() {
        return $this->getEntityManager()
            ->createQuery('
                SELECT g
                FROM AppBundle:Gallery g
                WHERE g.display = TRUE
                ORDER BY g.position ASC')
            ->getResult();
    }
}

