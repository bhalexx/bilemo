<?php

namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bilemo_client")
 * @ORM\Entity
 */
class Client extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Application
     *
     * @ORM\OneToOne(targetEntity="Application")
     */
    protected $application;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set application
     *
     * @param Application $application
     *
     * @return Client
     */
    public function setApplication(Application $application = null)
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
