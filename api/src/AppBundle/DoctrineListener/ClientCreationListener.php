<?php

namespace AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Services\ClientMailer;
use AppBundle\Entity\Client;

class ClientCreationListener
{
    /**
     * @var ClientMailer
     */
    private $clientMailer;

    public function __construct(ClientMailer $clientMailer)
    {
        $this->clientMailer = $clientMailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        //Only if entity is an instance of Client
        if (!$entity instanceof Client) {
            return;
        }

        //Send mail
        $this->clientMailer->sendCredentialsEmail($entity);
    }
}
