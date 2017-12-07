<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\DoctorFeedback;
use happy\CmsBundle\Entity\DoctorLog;
use happy\CmsBundle\Entity\DoctorPosition;
use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\DoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Nurse controller.
 *
 * @Route("/nurse")
 */
class NurseController extends Controller
{

    /* ======= APP HOME ======= */

    /**
     *  Lists all project entities.
     *
     * @Route("/service-type", name="api_nurse_service_type")
     * @Method("GET")
     *
     */
    public function servicetypeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorType')->createQueryBuilder('n');

        /**@var DoctorType[] $nurseType */
        $nurseType = $qb
            ->select('n.id', 'n.name')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse(
            array(
                "data" => $nurseType,
            )
        );
    }


    /**
     *  Lists all project entities.
     *
     * @Route("/position", name="api_nurse_position")
     * @Method("GET")
     *
     */
    public function positionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorPosition')->createQueryBuilder('n');

        /**@var DoctorPosition[] $doctorPosition */
        $doctorPosition = $qb
            ->select('n.id', 'n.name')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse(
            array(
                "data" => $doctorPosition,
            )
        );
    }

    /**
     *  Lists all project entities.
     *
     * @Route("/", name="api_nurse")
     * @Method({"GET", "POST"})
     *
     */
    public function nurseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $positionId = $request->request->get('position');
        $serviceId = $request->request->get('services');

        if ($positionId == null && $serviceId == null) {
            $positionId = 1;
            $serviceId = 1;
        }

        $qb = $em->getRepository('happyCmsBundle:DoctorQpay')->createQueryBuilder('n');
        /**@var Doctors[] $nurse */
        $nurse = $qb
            ->select('d.id as nurseId', 'd.name as nurseName', 'd.namtar as nurseNamtar', 'd.photo as nursePhoto', 'd.turshlaga as nurseTurshlaga', 'd.surguuli as NurseSurguuli', 'd.mergeshil as NurseMergeshil', 'dt.price as ServicePrice', 'dt.id as ServiceId', 'dp.id as PositionId')
            ->leftJoin('n.doctor', 'd')
            ->leftJoin('n.doctorType', 'dt')
            ->leftJoin('n.doctorPosition', 'dp')
            ->andWhere('dp.id = :posid')
            ->setParameter('posid', $positionId)
            ->andWhere('dt.id = :serid')
            ->setParameter('serid', $serviceId)
            ->andWhere('d.isDoctor = 0')
            ->andWhere('d.isShow = 1')
            ->orderBy('d.createdDate', 'asc')
            ->addOrderBy('d.star', 'desc')
            ->groupBy('d.id')
            ->getQuery()
            ->getArrayResult();

        foreach ($nurse as $key => $n) {
            if ($n['nursePhoto'] == null) {
                $nurse[$key]['nursePhoto'] = $this->container->getParameter('localstatfolder') . 'nurse-list.jpg';
            } else {
                $nurse[$key]['nursePhoto'] = $this->container->getParameter('localstatfolder') . $n['nursePhoto'];
            }
        }

        return new JsonResponse(
            array(
                "data" => $nurse,
                "Header" => 'Таны дуудлага бүртгэгдлээ.',
                "Body" => 'Та дуудлагаа баталгаажуулахын тулд манай үйлчилгээний данслуу урьдчилгаа төлбөр шилжүүлэх хэрэгтэй. Та доорх данслуу урьдчилгаа төлбөр шилжүүлнэ үү! ',
                "Info" => 'Хүлээн авах банк: Худалдаа хөгжлийн банк \n
                            Дансны дугаар: 472020237 \n
                            Данс эзэмшигч: Emneleg.mn \n
                            Дүн: 20000₮',
                "Phone" => 'Асуух зүйл байвал: 99031675',
                "Footer" => 'Та дуудлагаа баталгаажуулсан тохиолдолд бид 5 минутын дотор тантай эргэн холбогдох болно.'
            ),
            200, array(
                'Access-Control-Allow-Origin' => '*'
            )
        );
    }

    /**
     *  Lists all project entities.
     *
     * @Route("/log", name="api_nurse_log")
     * @Method({"GET", "POST"})
     *
     */
    public function nurselogAction(Request $request)
    {

        $nurseid = $request->request->get('nurseid');
        $nursename = $request->request->get('nursename');
        $nurseprice = $request->request->get('price');
        $service = $request->request->get('service');
        $position = $request->request->get('position');
        if ($nurseid == null) {
            return new JsonResponse(array(
                'status' => 'nurse id null bn',
            ));
        } else {
            $em = $this->container->get('doctrine')->getManager();
            $doctorlog = new DoctorLog();
            $doctorlog->setType($service);
            $doctorlog->setDoctorId($nurseid);
            $doctorlog->setDoctorName($nursename);
            $doctorlog->setPrice($nurseprice);
            $doctorlog->setPosition($position);
            $em->persist($doctorlog);
            $em->flush();
            return new JsonResponse(array(
                'status' => 'ok',
            ));
        }
    }

    /**
     *  Lists all project entities.
     *
     * @Route("/feedback", name="api_nurse_feedback")
     * @Method({"GET", "POST"})
     *
     */
    public function nurseFeedbackAction(Request $request)
    {
        $nurseid = $request->request->get('id');
        $content = $request->request->get('content');

        if ($nurseid == null) {
            return new JsonResponse(array(
                'status' => 'nurse id null bn',
            ));
        } else {
            $em = $this->container->get('doctrine')->getManager();
            $doctorFeedback = new DoctorFeedback();
            $doctorFeedback->setDoctorId($nurseid);
            $doctorFeedback->setContent($content);
            $em->persist($doctorFeedback);
            $em->flush();
            return new JsonResponse(array(
                'status' => 'ok',
            ));
        }


    }


}