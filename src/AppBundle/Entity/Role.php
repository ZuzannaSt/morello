<?php

/**
 * Role Entity
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class Role
 * @package AppBundle\Entity
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface
{
    /**
     * Id
     *
     * @access protected
     * @var
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name
     *
     * @access protected
     * @var
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="validations.name.not_blank")
     * @Assert\Length(min=3, minMessage="validations.name.min")
     */
    protected $name;

    /**
     * Role
     *
     * @access protected
     * @var
     *
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     * @Assert\NotBlank(message="validations.name.not_blank")
     */
    protected $role;

    /**
     * Users
     *
     * @access protected
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    protected $users;

    /**
     * Roles constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get role
     *
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
