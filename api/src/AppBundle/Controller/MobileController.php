<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Mobile;
use AppBundle\Representation\Mobiles;

class MobileController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/api/mobiles",
     *     name = "api_mobile_list"
     * )
     *
     * @Rest\QueryParam(
     *     name = "keyword",
     *     requirements = "[a-zA-Z0-9]",
     *     nullable = true,
     *     description = "The keyword to search for."
     * )
     * 
     * @Rest\QueryParam(
     *     name = "order",
     *     requirements = "asc|desc",
     *     default = "asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name = "limit",
     *     requirements = "\d+",
     *     default = "15",
     *     description = "Max number of mobiles per page."
     * )
     * @Rest\QueryParam(
     *     name = "offset",
     *     requirements = "\d+",
     *     default = "0",
     *     description = "The pagination offset"
     * )
     * 
     * @Rest\View(
     *     statusCode = 200
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository('AppBundle:Mobile')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Mobiles($pager);

        // $em = $this->getDoctrine()->getManager();

        // $mobilesList = $em->getRepository('AppBundle:Mobile')->findAll();

        // return $mobilesList;
    }

    /**
     * @Rest\Get(
     *     path = "/api/mobiles/{id}",
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
     *     path = "/api/mobiles",
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
     *     path = "/api/mobiles/{id}",
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
     *     path = "/api/mobiles/{id}",
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