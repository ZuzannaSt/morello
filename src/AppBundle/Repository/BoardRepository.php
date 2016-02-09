<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class BoardRepository
 * @package AppBundle\Repository
 */
class BoardRepository extends EntityRepository
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
                SELECT b
                FROM AppBundle:Board b
                ORDER BY b.name ASC
            ')
            ->getResult();
    }

    /**
     * Add entity
     *
     * @param board, project_id
     * @return entity
     */
    public function add($board, $project_id)
    {
        $project = $this->getEntityManager()
          ->getRepository('AppBundle:Project')
          ->findOneById($project_id);

        $board->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $board->setProject($project);
        $this->save($board);
    }

    /**
     * Save entity
     *
     * @param board
     * @return entity
     */
    public function save($board)
    {
        $em = $this->getEntityManager();
        $em->persist($board);
        $em->flush();
    }

    /**
     * Delete entity
     *
     * @param board
     */
    public function delete($board)
    {
        $em = $this->getEntityManager();
        $em->remove($board);
        $em->flush();
    }
}
