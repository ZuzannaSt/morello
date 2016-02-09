<?php

/**
 * Status Repository
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class StatusRepository
 * @package AppBundle\Repository
 */
class StatusRepository extends EntityRepository
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
                SELECT s
                FROM AppBundle:Status s
                ORDER BY s.name ASC
            ')
            ->getResult();
    }

    /**
     * Save entity
     *
     * @param status
     * @return entity
     */
    public function save($status)
    {
        $em = $this->getEntityManager();
        $em->persist($status);
        $em->flush();
    }

    /**
     * Delete entity
     *
     * @param delete
     * @return entity
     */
    public function delete($status)
    {
        $em = $this->getEntityManager();
        $em->remove($status);
        $em->flush();
    }
}
