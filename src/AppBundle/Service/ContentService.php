<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ContentService {

    /** @var  EntityManagerInterface */
    private $em;

    private $content = [];

    /**
     * SettingsService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get($key, $default = null) {
        $keys = explode('.', $key);
        $data = $this->getKeys($keys[0]);

        return isset($data[$key]) && $data[$key] ? $data[$key] : $default;
    }

    private function getKeys($root) {
        if (!isset($this->content[$root])) {
            $this->content[$root] = [];
            foreach ($this->getQuery($root)->getQuery()->getArrayResult() as $row) {
                $this->content[$root][$row['name']] = $row['value'];
            }
        }

        return $this->content[$root];
    }

    private function getQuery($root) {
        $qb = $this->em->getRepository('AppBundle:Content')->createQueryBuilder('c');

        $qb->where($qb->expr()->like('c.name', ':name'));
        $qb->setParameter('name', $root . '.%');

        return $qb;
    }

}