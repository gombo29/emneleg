<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\DoctorFeedback;
use happy\CmsBundle\Entity\DoctorLog;
use happy\CmsBundle\Entity\DoctorPosition;
use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\DoctorType;
use happy\CmsBundle\Entity\OnlineDoctorQuestion;
use happy\CmsBundle\Entity\OnlineDoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Nurse controller.
 *
 * @Route("/online-doctor")
 */
class OnlineDoctorController extends Controller
{

    /* ======= APP HOME ======= */

    /**
     *  Lists all project entities.
     *
     * @Route("/type", name="api_online_doctor_get_type")
     * @Method("GET")
     *
     */
    public function getTypeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorType')->createQueryBuilder('n');

        /**@var OnlineDoctorType[] $onlineDoctorType */
        $onlineDoctorType = $qb
            ->select('n.id', 'n.name')
            ->where('n.parent is null')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse(
            array(
                "data" => $onlineDoctorType,
            )
        );
    }


    /**
     *  Lists all project entities.
     *
     * @Route("/position/{id}", name="api_online_doctor_get_question", requirements={"id" = "\d+"})
     * @Method("GET")
     *
     */
    public function getQuestionByTypeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorType')->createQueryBuilder('n');

        /**@var DoctorPosition[] $doctorPosition */
        $doctorPosition = $qb
            ->select('n.id', 'n.name')
            ->where('n.parent = :pid')
            ->setParameter('pid', $id)
            ->andWhere('n.isFinish = 1')
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
     * @Route("/answers/{id}", name="api_nurse", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     *
     */
    public function getAnswersByQuestion(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /**@var OnlineDoctorQuestion[] $question */
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorQuestion')->createQueryBuilder('n');
        /**@var Doctors[] $nurse */
        $answers = $qb
            ->select('n.id', 'n.isLast', 'n.isFirst', 'n.descr', 'cy.id as YesId', 'cn.id as NoId')
            ->where('n.type = :tid')
            ->setParameter('tid', $id)
            ->leftJoin('n.childYes', 'cy')
//            ->addSelect('cy')
            ->leftJoin('n.childNo', 'cn')
//            ->addSelect('cn')
            ->getQuery()
            ->getArrayResult();

        foreach ($answers as $key=>$ans)
        {
            if($ans['isLast'] == null)
            {
                $answers[$key]['isLast'] = false;
            }

            if($ans['isFirst'] == null)
            {
                $answers[$key]['isFirst'] = false;
            }
        }

        return new JsonResponse(
            array(
                "data" => $answers,
            )
        );

    }




}