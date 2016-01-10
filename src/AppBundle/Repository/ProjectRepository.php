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
                'Unable to find an active admin AppBundle:User object identified by "%s".',
                $name
            );
            throw new NameNotFoundException($message);
        }

        return $project;
    }
}
