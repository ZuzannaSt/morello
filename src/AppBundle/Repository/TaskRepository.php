<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT t
                FROM AppBundle:Task t
                ORDER BY t.name ASC
            ')
            ->getResult();
    }

    public function add($task, $board_id)
    {
      $board = $this->getEntityManager()
          ->getRepository('AppBundle:Board')
          ->findOneById($board_id);

      $task->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
      $task->setBoard($board);
      $this->save($task);
    }

    public function save($task)
    {
        $em = $this->getEntityManager();
        $em->persist($task);
        $em->flush();
    }

    public function delete($task)
    {
        $em = $this->getEntityManager();
        $em->remove($task);
        $em->flush();
    }
}
