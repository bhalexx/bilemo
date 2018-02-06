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
		private $passwordGenerator;

		public function __construct(ApplicationPasswordGenerator $passwordGenerator)
		{
			$this->passwordGenerator = $passwordGenerator;
		}

		public function prePersist(LifecycleEventArgs $args)
		{
			$entity = $args->getObject();

			//Only if entity is an instance of Application
			if (!$entity instanceof Application) {
				return;
			}

			//Generate password
			$this->passwordGenerator->generatePassword($entity);
		}
	}