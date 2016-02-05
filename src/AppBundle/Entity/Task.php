<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
 class Task
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
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     */
    private $name;

    /**
     * @ORM\Column(
     *     name="description",
     *     type="string",
     *     length=128,
     *     nullable=true
     * )
     */
    private $description;

    /**
     * @ORM\Column(
     *     name="created_at",
     *     type="datetime",
     *     nullable=true
     * )
     */
    private $created_at;

    /**
     * @ORM\Column(
     *     name="updated_at",
     *     type="datetime",
     *     nullable=true
     * )
     */
    private $updated_at;

    /**
     * Tasks statuses
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Status", inversedBy="tasks", cascade={"persist"})
     * @ORM\JoinTable(name="tasks_statuses")
     */
    protected $statuses;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="projects")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
    * @ORM\ManyToMany(targetEntity="User", inversedBy="tasks")
    * @ORM\JoinTable(name="tasks_users")
    */
    protected $users;

    /**
    * Constructor
    */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->statuses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updated_at = new \DateTime(date('Y-m-d H:i:s'));
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
     * Set id
     *
     * @param integer id
     * @return Task
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * Set name
     *
     * @param string $name
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
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
     * @return Task
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
     * @return Task
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get project
     *
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project
     *
     * @param integer project
     * @return Task
     */
    public function setProject($project)
    {
        $this->project = $project;

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
     * @param \AppBundle\Entity\UserEntity $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }
}
