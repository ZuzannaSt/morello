<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ProjectRepository
 * @package AppBundle\Repository
 */
class ProjectRepository extends EntityRepository
{
    /**
     * Find all objects ordered by name
     *
     * @return result
     */
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

    /**
     * Count all objects
     *
     * @return scalar result
     */
    public function countAll()
    {
        return $this->getEntityManager()
          ->createQuery('
              SELECT
              COUNT(p.id)
              FROM AppBundle:Project p
          ')
          ->getSingleScalarResult();
    }

    /**
     * Add entity
     *
     * @param project
     * @return entity
     */
    public function add($project)
    {
        $project->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $this->save($project);
    }

    /**
     * Save entity
     *
     * @param project
     * @return entity
     */
    public function save($project)
    {
        $em = $this->getEntityManager();
        $em->persist($project);
        $em->flush();
    }

    /**
     * Delete entity
     *
     * @param project
     * @return entity
     */
    public function delete($project)
    {
        $em = $this->getEntityManager();
        $em->remove($project);
        $em->flush();
    }
}
