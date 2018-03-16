<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\OnlineDoctorQuestion;
use happy\CmsBundle\Entity\OnlineDoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class OnlineDoctorController extends Controller
{
    /**
     * @Route("/doctor", name="online_doctor")
     */
    public function homeAction()
    {
        return $this->render('@happyWeb/OnlineDoctor/index.html.twig');
    }

    /**
     * @Route("/doctor-question/{id}", name="online_doctor_question", requirements={"id" = "\d+"} )
     */
    public function questionAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorType')->createQueryBuilder('n');
        /**@var OnlineDoctorType[] $questions */
        $questions = $qb
            ->leftJoin('n.parent', 'p')
            ->addSelect('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->andWhere('n.isFinish = 1')
            ->getQuery()
            ->getArrayResult();
        $typeName = 'Тодорхойгүй';

        if (sizeof($questions) > 0) {
            $typeName = $questions[0]['parent']['name'];
        }

        return $this->render('@happyWeb/OnlineDoctor/question.html.twig', array('questions' => $questions, 'name' => $typeName));
    }

    /**
     * @Route("/doctor-answers/{id}", name="online_doctor_answers", requirements={"id" = "\d+"} )
     */
    public function answerAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorQuestion')->createQueryBuilder('n');

        if ($request->get('isfirst') == 1) {
            $qb->where('n.isFirst = 1')
                ->andWhere('n.type = :id')
                ->setParameter('id', $id);
        } else {
            $qb
                ->where('n.id = :id')
                ->setParameter('id', $id);
        }


        /**@var OnlineDoctorQuestion[] $answers */
        $answers = $qb
            ->leftJoin('n.childYes', 'yes')
            ->leftJoin('n.childNo', 'no')
            ->addSelect('yes')
            ->addSelect('no')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/OnlineDoctor/answers.html.twig', array('answers' => $answers));
    }
}
