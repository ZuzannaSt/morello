<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BoardRepository extends EntityRepository
{
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

    public function add($board, $project_id)
    {
        $project = $this->getEntityManager()
          ->getRepository('AppBundle:Project')
          ->findOneById($project_id);

        $board->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $board->setProject($project);
        $this->save($board);
    }

    public function save($board)
    {
        $em = $this->getEntityManager();
        $em->persist($board);
        $em->flush();
    }

    public function delete($board)
    {
        $em = $this->getEntityManager();
        $em->remove($board);
        $em->flush();
    }
}
