<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\User;

class UserController extends FOSRestController
{
    /**
     * @Security("has_role('ROLE_BILEMO')")
     *
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
     * @Security("has_role('ROLE_BILEMO')")
     *
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

    /**
     * @Rest\Post(
     *     path = "/api/users",
     *     name = "api_user_create"
     * )
     *
     * @Rest\View(
     *     statusCode = 201
     * )
     *
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function createAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Rest\Put(
     *     path = "/api/users/{id}",
     *     name = "api_user_update",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     *
     * @ParamConverter("newUser", converter="fos_rest.request_body")
     */
    public function updateAction(User $user, User $newUser)
    {
        $user->setUserName($newUser->getUserName());
        $user->setFirstname($newUser->getFirstname());
        $user->setLastname($newUser->getLastname());
        $user->setEmail($newUser->getEmail());
        $user->setPhone($newUser->getPhone());

        $this->getDoctrine()->getManager()->flush();

        return $user;
    }

    /**
     * @Rest\Delete(
     *     path = "/api/users/{userId}",
     *     name = "api_user_delete",
     *     requirements = { "userId" = "\d+" }
     * )
     *
     * @Rest\View(
     *     statusCode = 204
     * )
     *
     */
    public function deleteAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneById($userId);

        if ($user) {
            $em->remove($user);
            $em->flush();
        }

        return;
    }
}
