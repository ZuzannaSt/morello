<?php

namespace AppBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * Find all objects ordered by name
     *
     * @return result
     */
    public function findAllOrderedByUsername()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT u
                FROM AppBundle:User u
                ORDER BY u.username ASC
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
              COUNT(u.id)
              FROM AppBundle:User u
          ')
          ->getSingleScalarResult();
    }

    /**
     * Refresh user
     *
     * @param user
     * @return user_id
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    /**
     * Supports class
     *
     * @param class
     * @return entity name
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * Save entity
     *
     * @param user
     * @return entity
     */
    public function save($user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * Add default role to user
     *
     * @param user
     * @return entity
     */
    public function addDefaultRole($user)
    {
        $role = $this->getEntityManager()
          ->getRepository('AppBundle:Role')
          ->findOneBy(array('role' => 'ROLE_USER'));

        $user->addRole($role);
        $this->save($user);
    }

    /**
     * Delete entity
     *
     * @param user
     * @return entity
     */
    public function delete($user)
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
