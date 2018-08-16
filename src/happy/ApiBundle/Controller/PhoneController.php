<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * device controller.
 *
 * @Route("/phonenumber")
 */
class PhoneController extends Controller
{

    /**
     *
     * @Route("/", name="api_phone_number")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Phonenumber')->createQueryBuilder('u');
        $data = $qb
            ->select('u.phoneNumber')
            ->getQuery()
            ->getArrayResult();
        $arr = array();
        if (sizeof($data) > 0)
            foreach ($data as $datum) {
                array_push($arr, $datum['phoneNumber']);
            }


        return new JsonResponse(
            array(
                'status' => 'success',
                'message' => 'амжилттай',
                'data' => $arr
            ));
    }
}