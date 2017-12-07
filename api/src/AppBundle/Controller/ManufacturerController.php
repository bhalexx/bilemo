<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Manufacturer;

class ManufacturerController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/manufacturers",
     *     name = "api_manufacturer_list"
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $manufacturers = $em->getRepository('AppBundle:Manufacturer')->findAll();

        return $manufacturers;
    }

    /**
     * @Rest\Get(
     *     path = "/manufacturers/{id}",
     *     name = "api_manufacturer_view",
     *     requirements = { "id" = "\d+" }
     * )
     *
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function viewAction(Manufacturer $manufacturer)
    {
        return $manufacturer;
    }

    /**
     * @Rest\Post(
     *     path = "/manufacturers",
     *     name = "api_manufacturer_create"
     * )
     * @Rest\View(
     *     statusCode = 201
     * )
     *
     * @ParamConverter("manufacturer", converter="fos_rest.request_body")
     */
    public function createAction(Manufacturer $manufacturer)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($manufacturer);
        $em->flush();

        return $this->view(
            $manufacturer,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_manufacturer_view', ['id' => $manufacturer->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Put(
     *     path = "manufacturers/{id}",
     *     name = "api_manufacturer_update",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     *
     * @ParamConverter("newManufacturer", converter="fos_rest.request_body")
     */
    public function updateAction(Manufacturer $manufacturer, Manufacturer $newManufacturer)
    {
        $manufacturer->setName($newManufacturer->getName());
        $this->getDoctrine()->getManager()->flush();

        return $manufacturer;
    }

    /**
     * @Rest\Delete(
     *     path = "manufacturers/{id}",
     *     name = "api_manufacturer_delete",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 204
     * )
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $manufacturer = $em->getRepository('AppBundle:Manufacturer')->findOneById($id);

        if ($manufacturer) {
            $em->remove($manufacturer);
            $em->flush();
        }

        return;
    }
}
