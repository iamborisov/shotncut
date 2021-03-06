<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Blog;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class BlogRepository extends EntityRepository
{
    const PAGE_SIZE = 6;

    /**
     * @return Blog[]
     */
    public function findAllByPage($page = 1) {
        return $this->getEntityManager()
            ->createQuery('
                SELECT b
                FROM AppBundle:Blog b
                WHERE b.display = TRUE
                ORDER BY b.id DESC')
            ->setMaxResults(self::PAGE_SIZE)
            ->setFirstResult(self::PAGE_SIZE * ($page - 1))
            ->getResult();
    }

    /**
     * @param string $url
     * @return Blog
     */
    public function findByUrl($url) {
        try {
            return $this->getEntityManager()
                ->createQuery('
                    SELECT b
                    FROM AppBundle:Blog b
                    WHERE
                      b.display = TRUE
                      AND
                      b.url = :url')
                ->setParameter('url', $url)
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param int $id
     * @return Blog
     */
    public function findNextById($id) {
        try {
            return $this->getEntityManager()
                ->createQuery('
                    SELECT b
                    FROM AppBundle:Blog b
                    WHERE
                      b.display = TRUE
                      AND
                      b.id > :id
                    ORDER BY b.id ASC')
                ->setParameter('id', $id)
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param int $id
     * @return Blog
     */
    public function findPreviousById($id) {
        try {
            return $this->getEntityManager()
                ->createQuery('
                    SELECT b
                    FROM AppBundle:Blog b
                    WHERE
                      b.display = TRUE
                      AND
                      b.id < :id
                    ORDER BY b.id DESC')
                ->setParameter('id', $id)
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @return int
     */
    public function countPages() {
        $count = (int) $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(b.id)
                FROM AppBundle:Blog b
                WHERE b.display = TRUE')
            ->getSingleScalarResult();

        return ceil($count / self::PAGE_SIZE);
    }
}