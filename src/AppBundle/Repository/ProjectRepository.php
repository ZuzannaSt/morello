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
                'p.description',
                'u.username'
            )
            ->innerJoin(
                'p.users',
                'u',
                'WITH',
                'u.id = :user_id'
            )
            ->getQuery();
        return $query->getResult();
    }

    public function add($project)
    {
      $project->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
      $this->save($project);
    }

    public function save($project)
    {
        $em = $this->getEntityManager();
        $em->persist($project);
        $em->flush();
    }

    public function delete($project)
    {
        $em = $this->getEntityManager();
        $em->remove($project);
        $em->flush();
    }
}
