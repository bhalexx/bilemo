<?php

namespace AppBundle\DoctrineListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Product;

class MobileSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // Only act on "Product" entity
        if ($entity instanceof Product) {
            $entityManager = $args->getEntityManager();

            // Set dateInsert
           	$entity->setDateInsert(new \DateTime());

            // Set OS
            $os = $entityManager->getRepository('AppBundle:Os')->findOneById($entity->getOs());
            $entity->setOs($os);

	        // Set manufacturer
	        $manufacturer = $entityManager->getRepository('AppBundle:Manufacturer')->findOneById($entity->getManufacturer());
	        $entity->setManufacturer($manufacturer);

	        // Features
	        foreach($entity->getFeatures() as $feature) {
	        	$feature->setProduct($entity);
	        }
        }
    }
}
