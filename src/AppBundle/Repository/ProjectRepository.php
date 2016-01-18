<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function loadProjectByName($name)
    {
        $project = $this->createQueryBuilder('p')
            ->where('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $project) {
            $message = sprintf(
                'Unable to find an AppBundle:Project object identified by "%s".',
                $name
            );
            throw new NameNotFoundException($message);
        }

        return $project;
    }

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
                'pu.project = p.id'
            )
            ->innerJoin(
                'AppBundle:User',
                'u',
                'WITH',
                'pu.user = u.id'
            )
            ->getQuery();
        return $query->getResult();
    }
}
