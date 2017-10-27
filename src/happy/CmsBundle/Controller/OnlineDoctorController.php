<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorQpay;
use happy\CmsBundle\Entity\OnlineDoctorQuestion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/online-doctor")
 */
class OnlineDoctorController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/", name="cms_online_doctor" )
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorQuestion')->createQueryBuilder('n');
        /**@var DoctorQpay[] $nurseQr */
        $questions = $qb
            ->leftJoin('n.type', 't')
            ->addSelect('t')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyCms/OnlineDoctor/index.html.twig', array(
            'questions' => $questions,
        ));
    }

    /**
     * Creates a new NursePosition entity.
     *
     * @Route("/new", name="cms_online_doctor_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $question = new OnlineDoctorQuestion();
        $form = $this->createForm('happy\CmsBundle\Form\OnlineDoctorQuestionType', $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Асуулт амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_online_doctor');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_online_doctor_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, OnlineDoctorQuestion $question)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\OnlineDoctorQuestionType', $question);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Асуулт амжилттай засагдлаа!');
            return $this->redirectToRoute('cms_online_doctor');
        }

        return array(
            'edit_form' => $editForm->createView(),
        );
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}", name="cms_online_doctor_delete", requirements={"id" = "\d+"})
     * @Method("GET")
     */
    public function deleteAction(Request $request, OnlineDoctorQuestion $question)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($question);
        $em->flush();

        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Асуулт амжилттай устлаа!');
        return $this->redirectToRoute('cms_online_doctor');
    }

}
