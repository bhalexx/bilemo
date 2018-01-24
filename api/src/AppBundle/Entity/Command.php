<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Command
 *
 * @ORM\Table(name="bilemo_command")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandRepository")
 */
class Command
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
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="bilemo_command_product")
     */
    private $products;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Address")
     */
    private $deliveryAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Application")
     */
    private $application;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->products = new ArrayCollection();
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
     * Add product
     *
     * @param Product product
     *
     * @return Command
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * Remove product
     *
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return string
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Command
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set deliveryAddress
     *
     * @param Address $deliveryAddress
     *
     * @return Command
     */
    public function setDeliveryAddress(Address $deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get deliveryAddress
     *
     * @return Address
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Command
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set application
     *
     * @param Application $application
     *
     * @return Command
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }
}

