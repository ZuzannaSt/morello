<?php

/**
 * User Entity
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(
     *     type="integer",
     *     nullable=false,
     *     options={
     *      "unsigned"=true
     *     }
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(
     *     name="password",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     */
    private $password;

    /**
     * @Assert\NotBlank(message="validations.password.not_blank", groups={"registration"})
     * @Assert\Length(min=7, minMessage="validations.password.min", max = 4096, maxMessage="validations.password.max")
     */
    private $plainPassword;

    /**
     * @ORM\Column(
     *     name="username",
     *     type="string",
     *     length=128,
     *     unique=true,
     *     nullable=false
     * )
     * @Assert\NotBlank(message="validations.username.not_blank")
     * @Assert\Length(min=3, minMessage="validations.username.min")
     */
    private $username;

    /**
     * @ORM\Column(
     *     name="email",
     *     type="string",
     *     length=128,
     *     unique=true,
     *     nullable=false
     * )
     * @Assert\NotBlank(message="validations.email.not_blank", groups={"registration"})
     * @Assert\Email(groups={"registration"})
     */
    private $email;

    /**
     * @ORM\Column(
     *     name="is_active",
     *     type="boolean"
     *     )
     */
    private $isActive;

    /**
     * @ORM\Column(
     *     name="firstname",
     *     type="string",
     *     length=128,
     *     nullable=true
     * )
     * @Assert\Length(min=2, minMessage="validations.firstname.min")
     */
    private $firstName;

    /**
     * @ORM\Column(
     *     name="lastname",
     *     type="string",
     *     length=128,
     *     nullable=true
     * )
     * @Assert\Length(min=2, minMessage="validations.lastname.min")
     */
    private $lastName;

    /**
     * User's projects
     *
     * @var ArrayCollection
    * @ORM\ManyToMany(targetEntity="Project", mappedBy="users")
    */
    protected $projects;

    /**
     * User's roles
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="users_roles")
     */
    protected $roles;

    /**
    * Users constructor
    */
    public function __construct()
    {
        $this->isActive = true;
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param integer id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
    * Get projects
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add projects
     *
     * @param \AppBundle\Entity\Project $projects
     * @return Project
     */
    public function addProject(\AppBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Add roles
     *
     * @param \AppBundle\Entity\Role $roles
     * @return Role
     */
    public function addRole(\AppBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \AppBundle\Entity\Role $roles
     */
    public function removeRole(\AppBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Remove projects
     *
     * @param \AppBundle\Entity\Project $projects
     */
    public function removeProject(\AppBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get password salt
     *
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        if (empty($this->roles)) {
            return array();
        } else {
            return $this->roles->toArray();
        }
    }

    /**
     * Erase users credentials
     *
     */
    public function eraseCredentials()
    {
    }

    /**
     * Serialize user
     *
     * @return array
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * Unserialize user
     *
     * @return string
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }

    /**
     * Get plain password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set plain password
     *
     * @param password
     * @return string
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set password
     *
     * @param password
     * @return string
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
