<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mobile
 *
 * @ORM\Table(name="mobile")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MobileRepository")
 */
class Mobile extends Product
{
    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Os")
     */
    private $os;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="colorName", type="string", length=255)
     */
    private $colorName;

    /**
     * @var string
     *
     * @ORM\Column(name="colorCode", type="string", length=255)
     */
    private $colorCode;

    /**
     * @var int
     *
     * @ORM\Column(name="memory", type="integer")
     */
    private $memory;


    /**
     * Set os
     *
     * @param Os $os
     *
     * @return Mobile
     */
    public function setOs(Os $os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * Get os
     *
     * @return Os
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Mobile
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set colorName
     *
     * @param string $colorName
     *
     * @return Mobile
     */
    public function setColorName($colorName)
    {
        $this->colorName = $colorName;

        return $this;
    }

    /**
     * Get colorName
     *
     * @return string
     */
    public function getColorName()
    {
        return $this->colorName;
    }

    /**
     * Set colorCode
     *
     * @param string $colorCode
     *
     * @return Mobile
     */
    public function setColorCode($colorCode)
    {
        $this->colorCode = $colorCode;

        return $this;
    }

    /**
     * Get colorCode
     *
     * @return string
     */
    public function getColorCode()
    {
        return $this->colorCode;
    }

    /**
     * Set memory
     *
     * @param integer $memory
     *
     * @return Mobile
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * Get memory
     *
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }
}

