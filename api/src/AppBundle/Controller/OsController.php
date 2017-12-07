<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Os;

class OsController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/os",
     *     name = "api_os_list"
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $oslist = $em->getRepository('AppBundle:Os')->findAll();

        return $oslist;
    }

    /**
     * @Rest\Get(
     *     path = "/os/{id}",
     *     name = "api_os_view",
     *     requirements = { "id" = "\d+" }
     * )
     *
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function viewAction(Os $os)
    {
        return $os;
    }

    /**
     * @Rest\Post(
     *     path = "/os",
     *     name = "api_os_create"
     * )
     * @Rest\View(
     *     statusCode = 201
     * )
     *
     * @ParamConverter("os", converter="fos_rest.request_body")
     */
    public function createAction(Os $os)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($os);
        $em->flush();

        return $this->view(
            $os,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_os_view', ['id' => $os->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Put(
     *     path = "/os/{id}",
     *     name = "api_os_update",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     *
     * @ParamConverter("newOs", converter="fos_rest.request_body")
     */
    public function updateAction(Os $os, Os $newOs)
    {
        $os->setName($newOs->getName());
        $os->setVersion($newOs->getVersion());
        $this->getDoctrine()->getManager()->flush();

        return $os;
    }

    /**
     * @Rest\Delete(
     *     path = "/os/{id}",
     *     name = "api_os_delete",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 204
     * )
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $os = $em->getRepository('AppBundle:Os')->findOneById($id);

        if ($os) {
            $em->remove($os);
            $em->flush();
        }

        return;
    }
}