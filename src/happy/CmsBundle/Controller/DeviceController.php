<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Banner;
use happy\CmsBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Apns as ApnsAdapter,
    Sly\NotificationPusher\Adapter\Gcm as GcmAdapter,
    Sly\NotificationPusher\Collection\DeviceCollection,
    Sly\NotificationPusher\Model\Message,
    Sly\NotificationPusher\Model\Push;
use Zend\Json\Json;

/**
 * DeviceController controller.
 *
 * @Route("/device")
 */
class DeviceController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{page}", name="cms_device_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;


        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Device')->createQueryBuilder('n');


        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();

        /**@var Device[] $device */
        $device = $qb
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyCmsBundle:Device:index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'device' => $device,
        ));
    }


    /**
     * Creates a new device entity.
     *
     * @Route("/new", name="cms_device_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function sendAction(Request $request)
    {

        if ($request->getMethod() == 'POST') {
            $text = $request->request->get('text');
            $appType = $request->request->get('typepush');

            $em = $this->container->get('doctrine')->getManager();

//            $pushManager = new PushManager(PushManager::ENVIRONMENT_PROD);
            $pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);


            if ($appType == 'emneleg') { //customer
                $apnsAdapter = new ApnsAdapter(array(
                    'certificate' => $path = $this->get('kernel')->getRootDir() . '/../web/myancash_customer_dev.pem'
                ));

                $gcmAdapter = new GcmAdapter(array(
                    'apiKey' => 'AIzaSyBWqdBqFudZJQyP1ktwExhP7imkDv1ZgCw'
                ));
            } else if ($appType == 'suvilagch') {
                $apnsAdapter = new ApnsAdapter(array(
                    'certificate' => $path = $this->get('kernel')->getRootDir() . '/../web/myancash_customer_dev.pem'
                ));


                $gcmAdapter = new GcmAdapter(array(
                    'apiKey' => 'AIzaSyBWqdBqFudZJQyP1ktwExhP7imkDv1ZgCw'
                ));
            } else if ($appType == 'emch') {
                $apnsAdapter = new ApnsAdapter(array(
                    'certificate' => $path = $this->get('kernel')->getRootDir() . '/../web/myancash_customer_dev.pem'
                ));


                $gcmAdapter = new GcmAdapter(array(
                    'apiKey' => 'AIzaSyBWqdBqFudZJQyP1ktwExhP7imkDv1ZgCw'
                ));
            }


            $qb = $em->getRepository('happyCmsBundle:Device')->createQueryBuilder('p');
            $device = $qb
                ->andWhere('p.appType = :appType')
                ->setParameter('appType', $appType)
                ->orderBy('p.id', 'desc')
                ->getQuery()
                ->getResult();

            if ($device == null) {
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('danger', 'Төхөөрөмж олдсонгүй!');
            } else {
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('success', 'Мэдээлэл илгээгдлээ!');
            }

            $iosdevices = array();
            $androiddevices = array();

            foreach ($device as $key => $d) {
                if ($d->getIsIOS() == true) {
                    array_push($iosdevices, new Device($d->getDeviceToken()));

                } else {
                    array_push($androiddevices, new Device($d->getDeviceToken()));
                }
            }

            $devices = new DeviceCollection($iosdevices);
            $gcmdevices = new DeviceCollection($androiddevices);

            $message = new Message($text);

            $push = new Push($apnsAdapter, $devices, $message);
            $gcmpush = new Push($gcmAdapter, $gcmdevices, $message);

            $pushManager->add($push);
            $pushManager->add($gcmpush);

            $pushManager->push();


        }

    }
}
