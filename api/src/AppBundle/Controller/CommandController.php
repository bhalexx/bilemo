<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Command;

class CommandController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/api/commands",
     *     name = "api_command_list"
     * )
     *
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commands = $em->getRepository('AppBundle:Command')->findAll();

        return $commands;
    }

    /**
     * @Rest\Get(
     *     path = "/api/commands/{id}",
     *     name = "api_command_view",
     *     requirements = { "id" = "\d+" }
     * )
     *
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function viewAction(Command $command)
    {
        return $command;
    }

    /**
     * @Rest\Post(
     *     path = "/api/commands",
     *     name = "api_command_create"
     * )
     * @Rest\View(
     *     statusCode = 201
     * )
     *
     * @ParamConverter("command", converter="fos_rest.request_body")
     *
     */
    public function createAction(Command $command)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($command);
        $em->flush();

        return $this->view(
            $command,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_command_view', ['id' => $command->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Put(
     *     path = "/api/commands/{id}",
     *     name = "api_command_update",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     *
     * @ParamConverter("newCommand", converter="fos_rest.request_body")
     *
     */
    public function updateAction(Command $command, Command $newCommand)
    {
        $command->setName($newCommand->getName());
        $this->getDoctrine()->getManager()->flush();

        return $command;
    }

    /**
     * @Rest\Delete(
     *     path = "/api/commands/{id}",
     *     name = "api_command_delete",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 204
     * )
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $command = $em->getRepository('AppBundle:Command')->findOneById($id);

        if ($command) {
            $em->remove($command);
            $em->flush();
        }

        return;
    }
}
