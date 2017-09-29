<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Banner;
use happy\CmsBundle\Entity\Device;
use Sly\NotificationPusher\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../Util/push.php';


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


            if ($appType == 'emneleg') {

                $path = $this->get('kernel')->getRootDir() . '/../web/6prod.pem';
                $androidKey = 'AIzaSyAmUyIAQEmH_6baO35FokRH3u1OqhGGtnQ';

            } else if ($appType == 'suvilagch') {

                $path = $this->get('kernel')->getRootDir() . '/../web/6prod.pem';
                $androidKey = 'AIzaSyAmUyIAQEmH_6baO35FokRH3u1OqhGGtnQ';

            } else if ($appType == 'emch') {
                $path = $this->get('kernel')->getRootDir() . '/../web/6prod.pem';
                $androidKey = 'AIzaSyAmUyIAQEmH_6baO35FokRH3u1OqhGGtnQ';
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

            foreach ($device as $key => $d) {
                if ($d->getIsIOS() == true) {
                    iOS($text, $d->getDeviceToken(), $path);
                } else {
                    android($text, $d->getDeviceToken(), $androidKey);
                }
            }
        }
    }
}
