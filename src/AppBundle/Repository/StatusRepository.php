<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StatusRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT s
                FROM AppBundle:Status s
                ORDER BY s.name ASC
            ')
            ->getResult();
    }

    public function save($status)
    {
        $em = $this->getEntityManager();
        $em->persist($status);
        $em->flush();
    }

    public function delete($status)
    {
        $em = $this->getEntityManager();
        $em->remove($status);
        $em->flush();
    }
}
