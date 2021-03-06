<?php

/**
 * Project Entity
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
 * Class Project
 * @package AppBundle\Entity
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
     /**
     * Id
     *
     * @access protected
     * @var
     *
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
    protected $id;

     /**
     * Name
     *
     * @access protected
     * @var
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     * @Assert\NotBlank(message="validations.name.not_blank")
     * @Assert\Length(min=3, minMessage="validations.name.min")
     */
    protected $name;

     /**
     * Description
     *
     * @access protected
     * @var
     *
     * @ORM\Column(
     *     name="description",
     *     type="string",
     *     length=128,
     *     nullable=true
     * )
     */
    protected $description;

    /**
     * Created at
     *
     * @access protected
     * @var
     *
     * @ORM\Column(
     *     name="created_at",
     *     type="datetime",
     *     nullable=true
     * )
     */
    protected $created_at;

    /**
     * Updated at
     *
     * @access protected
     * @var
     *
     * @ORM\Column(
     *     name="updated_at",
     *     type="datetime",
     *     nullable=true
     * )
     */
    protected $updated_at;

    /**
    * Updated at
    *
    * @access protected
    * @var \Doctrine\Common\Collections\ArrayCollection
    *
    * @ORM\ManyToMany(targetEntity="User", inversedBy="projects")
    * @ORM\JoinTable(name="projects_users")
    */
    protected $users;

    /**
     * Updated at
     *
     * @access protected
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Board", mappedBy="project", cascade={"remove"})
     */
    protected $boards;

    /**
    * Projects constructor
    */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->boards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updated_at = new \DateTime(date('Y-m-d H:i:s'));
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
     * @return Project
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set description
     *
     * @param string description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Get created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set created_at
     *
     * @param integer created_at
     * @return Project
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set updated_at
     *
     * @param integer updated_at
     * @return Project
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
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

    /**
     * Add users
     *
     * @param \AppBundle\Entity\UserEntity $users
     * @return User
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
    * Get boards
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add boards
     *
     * @param \AppBundle\Entity\Board $boards
     * @return Board
     */
    public function addBoard(\AppBundle\Entity\Board $boards)
    {
        $this->tasks[] = $boards;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \AppBundle\Entity\Board $boards
     */
    public function removeBoard(\AppBundle\Entity\Board $boards)
    {
        $this->tasks->removeElement($boards);
    }
}
