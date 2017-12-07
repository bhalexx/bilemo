<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

/**
 * Product
 *
 * @ORM\Table(name="bilemo_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"mobile" = "Mobile"})
 */
abstract class Product
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Manufacturer")
     */
    private $manufacturer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInsert", type="datetime")
     */
    private $dateInsert;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Picture", mappedBy="product")
     */
    private $pictures;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Feature", mappedBy="product")
     */
    private $features;

    public function __construct()
    {
        $this->dateInsert = new \DateTime();
        $this->pictures = new ArrayCollection();
        $this->features = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
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
     * Set manufacturer
     *
     * @param Manufacturer $manufacturer
     *
     * @return Product
     */
    public function setManufacturer(Manufacturer $manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return Manufacturer
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert
     *
     * @return Product
     */
    public function setDateInsert($dateInsert)
    {
        $this->dateInsert = $dateInsert;

        return $this;
    }

    /**
     * Get dateInsert
     *
     * @return \DateTime
     */
    public function getDateInsert()
    {
        return $this->dateInsert;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add picture
     *
     * @param Picture $picture
     *
     * @return Product
     */
    public function addPicture(Picture $picture)
    {
        $this->pictures[] = $picture;
        return $this;
    }

    /**
     * Remove picture
     *
     * @param Picture $picture
     */
    public function removePicture(Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Get pictures
     *
     * @return string
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Add feature
     *
     * @param Feature feature
     *
     * @return Product
     */
    public function addFeature(Feature $feature)
    {
        $this->features[] = $feature;
        return $this;
    }

    /**
     * Remove feature
     *
     * @param Feature feature
     */
    public function removeFeature(Feature $feature)
    {
        $this->features->removeElement($feature);
    }

    /**
     * Get features
     *
     * @return string
     */
    public function getFeatures()
    {
        return $this->features;
    }
}

