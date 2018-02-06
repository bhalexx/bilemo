<?php

	namespace AppBundle\DoctrineListener;
	
	use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
	use AppBundle\Services\ApplicationPasswordGenerator;
	use AppBundle\Entity\Application;

	class ApplicationCreationListener
	{		
		/**
		 * @var ApplicationPasswordGenerator
		 */
		private $applicationPasswordGenerator;

		public function __construct(ApplicationPasswordGenerator $applicationPasswordGenerator)
		{
			$this->applicationPasswordGenerator = $applicationPasswordGenerator;
		}

		public function prePersist(LifecycleEventArgs $args)
		{
			$entity = $args->getObject();

			//Only if entity is an instance of Application
			if (!$entity instanceof Application) {
				return;
			}

			//Generate password
			$this->applicationPasswordGenerator->generatePassword($entity);
		}
	}