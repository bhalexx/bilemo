<?php


namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\Application;

class ApplicationController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/api/applications",
     *     name = "api_application_list"
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $applications = $em->getRepository('AppBundle:Application')->findAll();

        return $applications;
    }

    /**
     * @Rest\Get(
     *     path = "/api/applications/{id}",
     *     name = "api_application_view",
     *     requirements = { "id" = "\d+" }
     * )
     *
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function viewAction(Application $application)
    {
        return $application;
    }

    /**
     * @Rest\Post(
     *     path = "/api/applications",
     *     name = "api_application_create"
     * )
     * @Rest\View(
     *     statusCode = 201
     * )
     *
     * @ParamConverter("application", converter="fos_rest.request_body")
     */
    public function createAction(Application $application, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        //Todo: remove this from here - create service to do this + send email with client's credentials
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $plainPassword = substr(str_shuffle($chars), 0, 25);
        $encoded = $encoder->encodePassword($application, $plainPassword);
        $application->setPassword($encoded);

        $em->persist($application);
        $em->flush();

        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array($application->getUri()));
        $client->setAllowedGrantTypes(array('password'));
        $client->setApplication($application);
        $clientManager->updateClient($client);

        return $this->view(
            [$application, $client],
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_application_view', ['id' => $application->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Put(
     *     path = "/api/applications/{id}",
     *     name = "api_application_update",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     *
     * @ParamConverter("newApplication", converter="fos_rest.request_body")
     */
    public function updateAction(Application $application, Application $newApplication)
    {
        $application->setName($newApplication->getName());
        $application->setEmail($newApplication->getEmail());
        $application->setUri($newApplication->getUri());
        $this->getDoctrine()->getManager()->flush();

        return $application;
    }

    /**
     * @Rest\Delete(
     *     path = "/api/applications/{id}",
     *     name = "api_application_delete",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 204
     * )
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $application = $em->getRepository('AppBundle:Application')->findOneById($id);

        if ($application) {
            $em->remove($application);
            $em->flush();
        }

        return;
    }
}