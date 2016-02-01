<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT r
                FROM AppBundle:Role r
                ORDER BY r.name ASC
            ')
            ->getResult();
    }

    public function save($role)
    {
        $em = $this->getEntityManager();
        $em->persist($role);
        $em->flush();
    }

    public function delete($role)
    {
        $em = $this->getEntityManager();
        $em->remove($role);
        $em->flush();
    }
}
