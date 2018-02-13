<?php

	namespace AppBundle\Services;
	
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use AppBundle\Entity\Application;

	class ApplicationPasswordGenerator
	{
		/**
		 * @var UserPasswordEncoderInterface
		 */
		private $encoder;

		public function __construct(UserPasswordEncoderInterface $encoder)
		{
			$this->encoder = $encoder;
		}

		public function generatePassword(Application $application)
		{
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$plainPassword = $application->getPlainPassword() ?: substr(str_shuffle($chars), 0, 25);
	        $fullPlainPassword = $application->getPlainPassword() ?: $application->getUsername()."_".$plainPassword;
	        $encoded = $this->encoder->encodePassword($application, $fullPlainPassword);
	        $application->setPlainPassword($fullPlainPassword);
	        $application->setPassword($encoded);	
		}
	}