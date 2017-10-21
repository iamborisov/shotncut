<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class ProjectRepository extends EntityRepository
{
    /**
     * @return Project[]
     */
    public function findAllVisible() {
        return $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM AppBundle:Project p
                WHERE p.display = TRUE
                ORDER BY p.position ASC')
            ->getResult();
    }

    /**
     * @param string $url
     * @return Project
     */
    public function findByUrl($url) {
        try {
            return $this->getEntityManager()
                ->createQuery('
                    SELECT p
                    FROM AppBundle:Project p
                    WHERE
                      p.display = TRUE
                      AND
                      p.url = :url')
                ->setParameter('url', $url)
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}

