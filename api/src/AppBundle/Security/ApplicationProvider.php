<?php

namespace AppBundle\Security;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Entity\Application;

class ApplicationProvider implements UserProviderInterface
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        $application = $this->em->getRepository('AppBundle:Application')->findOneByUsername($username);

        if (!$application) {
            throw new \LogicException("Unexisting application");
        }
        return $application;
    }

    public function refreshUser(UserInterface $application)
    {
        if (!$application instanceof Application) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($application))
            );
        }
        return $this->loadUserByUsername($application->getName());
    }

    public function supportsClass($class)
    {
        return Application::class === $class;
    }
}
