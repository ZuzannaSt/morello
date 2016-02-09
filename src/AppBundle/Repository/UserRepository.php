<?php

namespace AppBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserProviderInterface
{
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

    public function loadUserByUsername($username)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            $message = sprintf(
                'Unable to find an active admin AppBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }

        return $user;
    }

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

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }

    public function save($user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function addDefaultRole($user)
    {
        $role = $this->getEntityManager()
          ->getRepository('AppBundle:Role')
          ->findOneBy(array('role' => 'ROLE_USER'));

        $user->addRole($role);
        $this->save($user);
    }

    public function delete($user)
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
