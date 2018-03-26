<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\User;

/**
 * @Security("has_role('ROLE_BILEMO')")
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/api/users",
     *     name = "api_user_list"
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usersList = $em->getRepository('AppBundle:User')->findAll();

        return $usersList;
    }

    /**
     * @Rest\Get(
     *     path = "/api/users/{id}",
     *     name = "api_user_view",
     *     requirements = { "id" = "\d+" }
     * )
     *
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function viewAction(User $user)
    {
        return $user;
    }
}