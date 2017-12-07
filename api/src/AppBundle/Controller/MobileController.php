<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Mobile;

class MobileController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/mobiles",
     *     name = "api_mobile_list"
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $mobilesList = $em->getRepository('AppBundle:Mobile')->findAll();

        return $mobilesList;
    }

    /**
     * @Rest\Get(
     *     path = "/mobiles/{id}",
     *     name = "api_mobile_view",
     *     requirements = { "id" = "\d+" }
     * )
     *
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function viewAction(Mobile $mobile)
    {
        return $mobile;
    }

    /**
     * @Rest\Post(
     *     path = "/mobiles",
     *     name = "api_mobile_create"
     * )
     * @Rest\View(
     *     statusCode = 201
     * )
     *
     * @ParamConverter("mobile", converter="fos_rest.request_body")
     */
    public function createAction(Mobile $mobile)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($mobile);
        $em->flush();

        return $this->view(
            $mobile,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_mobile_view', ['id' => $mobile->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]
        );
    }

    /**
     * @Rest\Put(
     *     path = "/mobiles/{id}",
     *     name = "api_mobile_update",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 200
     * )
     *
     * @ParamConverter("newMobile", converter="fos_rest.request_body")
     */
    public function updateAction(Mobile $mobile, Mobile $newMobile)
    {
        $mobile->setName($newMobile->getName());
        $mobile->setManufacturer($newMobile->getManufacturer());
        $mobile->setStock($newMobile->getStock());
        $mobile->setOs($newMobile->getOs());
        $mobile->setPrice($newMobile->getPrice());
        $mobile->setColorName($newMobile->getColorName());
        $mobile->setColorCode($newMobile->getColorCode());
        $mobile->setMemory($newMobile->setMemory());

        $this->getDoctrine()->getManager()->flush();

        return $mobile;
    }

    /**
     * @Rest\Delete(
     *     path = "/mobiles/{id}",
     *     name = "api_mobile_delete",
     *     requirements = { "id" = "\d+" }
     * )
     * @Rest\View(
     *     statusCode = 204
     * )
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mobile = $em->getRepository('AppBundle:Mobile')->findOneById($id);

        if ($mobile) {
            $em->remove($mobile);
            $em->flush();
        }

        return;
    }
}