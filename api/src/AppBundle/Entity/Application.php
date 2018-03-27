<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * Application
 *
 * @ORM\Table(name="bilemo_application")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApplicationRepository")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_application_view",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *     "list",
 *     href = @Hateoas\Route(
 *         "api_application_list",
 *         absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "create",
 *     href = @Hateoas\Route(
 *         "api_application_create",
 *         absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "update",
 *     href = @Hateoas\Route(
 *         "api_application_update",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "delete",
 *     href = @Hateoas\Route(
 *         "api_application_delete",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 */
class Application implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(message = "Application name is required.")
     *
     * @Serializer\Since("1.0")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\NotBlank(message = "Application email is required.")
     * @Assert\Email()
     *
     * @Serializer\Since("1.0")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Serializer\Exclude
     */
    private $password;

    /**
     * @var string
     *
     * @Serializer\Exclude
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=255)
     *
     * @Assert\NotBlank(message = "Application uri is required.")
     * @Assert\Url()
     *
     * @Serializer\Since("1.0")
     */
    private $uri;

    /**
     * @ORM\Column(name="roles", type="array")
     *
     * @Serializer\Since("1.0")
     */
    private $roles = array();

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="applications")
     *
     * @Serializer\Since("1.0")
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Application
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
     * Set uri
     *
     * @param string $uri
     *
     * @return Application
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return Application
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Application
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Application
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set plainPassword
     *
     * @param string $plainPassword
     *
     * @return Application
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plainPassword
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Get users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add user
     *
     * @param User user
     *
     * @return Command
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        return $this;
    }
    /**
     * Remove user
     *
     * @param User $application
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }
}
