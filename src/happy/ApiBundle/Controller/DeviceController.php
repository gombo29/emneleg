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
 * @Route("/device")
 */
class DeviceController extends Controller
{

    /**
     * Creates a new Device entity.
     *
     * @Route("/new", name="api_device_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $deviceId = $request->get('deviceid');
        $deviceToken = $request->get('devicetoken');
        $deviceName = $request->get('devicename');
        $osVersion = $request->get('osVersion');
        $deviceModel = $request->get('devicemodel');
        $isIOS = $request->get('isiphone');
        $status = $request->get('status');
        $apptype = $request->get('type');

        if ($deviceId) {
            $qb = $em->getRepository('happyCmsBundle:Device')->createQueryBuilder('u');
            $device = $qb
                ->where('u.deviceToken = :id')
                ->setParameter('id', $deviceToken)
                ->andWhere('u.appType = :type')
                ->setParameter('type', $apptype)
                ->getQuery()
                ->getOneOrNullResult();

            if (!$device) {
                $deviceinfo = new Device();
                $deviceinfo->setCreatedDate(new \DateTime('now'));
                $deviceinfo->setDeviceId($deviceId);
                $deviceinfo->setDeviceToken($deviceToken);
                $deviceinfo->setDeviceName($deviceName);
                $deviceinfo->setOsVersion($osVersion);
                $deviceinfo->setDeviceModel($deviceModel);
                $deviceinfo->setIsIOS($isIOS);
                $deviceinfo->setStatus($status);
                $deviceinfo->setAppType($apptype);
                $em->persist($deviceinfo);
                $em->flush();
                return new JsonResponse(array('status' => 'success'));
            } else {
                return new JsonResponse(array('status' => 'device already registered'));
            }
        } else {
            return new JsonResponse(array('status' => 0));
        }
    }
}