<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class RoleRepository
 * @package AppBundle\Repository
 */
class RoleRepository extends EntityRepository
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
                SELECT r
                FROM AppBundle:Role r
                ORDER BY r.name ASC
            ')
            ->getResult();
    }

    /**
     * Save entity
     *
     * @param role
     * @return entity
     */
    public function save($role)
    {
        $em = $this->getEntityManager();
        $em->persist($role);
        $em->flush();
    }

    /**
     * Delete entity
     *
     * @param role
     * @return entity
     */
    public function delete($role)
    {
        $em = $this->getEntityManager();
        $em->remove($role);
        $em->flush();
    }
}
