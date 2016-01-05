<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class UserEntity
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
    protected $id;

    /**
     * @ORM\Column(
     *     type="integer",
     *     nullable=false,
     *     options={
     *      "unsigned"=true
     *     }
     * )
     */
    protected $password;
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */

    /**
     * @ORM\Column(
     *     name="username",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     */
    protected $userName;

    /**
     * @ORM\Column(
     *     name="email",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     */
    protected $email;

    /**
     * @ORM\Column(
     *     name="firstname",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     */
    protected $firstName;

    /**
     * @ORM\Column(
     *     name="lastname",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     */
    protected $lastName;

