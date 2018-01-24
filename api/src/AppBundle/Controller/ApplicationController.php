<?php


namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Application;

class ApplicationController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/applications",
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
     *     path = "/applications/{id}",
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
     *     path = "/applications",
     *     name = "api_application_create"
     * )
     * @Rest\View(
     *     statusCode = 201
     * )
     *
     * @ParamConverter("application", converter="fos_rest.request_body")
     */
    public function createAction(Application $application)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->persist($application);
        $em->flush();

        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array($application->getUri()));
        $client->setAllowedGrantTypes(array('authorization_code'));
        $clientManager->updateClient($client);

        // return $this->generateUrl('fos_oauth_server_authorize', array(
        //     'client_id'     => $client->getPublicId(),
        //     'redirect_uri'  => $application->getUri(),
        //     'response_type' => 'code'
        // ));

        return $this->view(
            $application,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_application_view', ['id' => $application->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Put(
     *     path = "/applications/{id}",
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
     *     path = "/applications/{id}",
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