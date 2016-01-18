<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM AppBundle:Project p
                ORDER BY p.name ASC
            ')
            ->getResult();
    }

    public function findAllCustom()
    {
        $query = $this->createQueryBuilder('p')
            ->select(
                'p.id',
                'p.name',
                'u.username'
            )
            ->innerJoin(
                'AppBundle:ProjectUser',
                'pu',
                'WITH',
                'pu.project_id = p.id'
            )
            ->innerJoin(
                'AppBundle:User',
                'u',
                'WITH',
                'pu.user_id = u.id'
            )
            ->getQuery();
        return $query->getResult();
    }

    public function find($id)
    {
        $query = $this->createQueryBuilder('p')
            ->select(
                'p.id',
                'p.name',
                'u.username'
            )
            ->innerJoin(
                'AppBundle:ProjectUser',
                'pu',
                'WITH',
                'pu.project_id = p.id'
            )
            ->innerJoin(
                'AppBundle:User',
                'u',
                'WITH',
                'pu.user_id = u.id'
            )
            ->getQuery();
        return $query->getResult();
    }
}
