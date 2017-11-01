<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorQpay;
use happy\CmsBundle\Entity\OnlineDoctorQuestion;
use happy\CmsBundle\Entity\OnlineDoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/online-doctor-type")
 */
class OnlineDoctorTypeController extends Controller
{
    /**
     *
     *  Lists all content entities.
     *
     * @Route("/", name="cms_online_doctor_type")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorType')->createQueryBuilder('n');
        /**@var OnlineDoctorType[] $type */
        $type = $qb
            ->leftJoin('n.children', 'c')
            ->addSelect('c')
            ->where('n.parent is null')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyCms/OnlineDoctorType/index.html.twig', array(
            'type' => $type,
        ));
    }

    /**
     * Creates a new NursePosition entity.
     *
     * @Route("/new", name="cms_online_doctor_type_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $question = new OnlineDoctorType();
        $form = $this->createForm('happy\CmsBundle\Form\OnlineDoctorTypeType', $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Төрөл амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_online_doctor_type');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_online_doctor_type_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, OnlineDoctorType $question)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\OnlineDoctorTypeType', $question);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Төрөл амжилттай засагдлаа!');
            return $this->redirectToRoute('cms_online_doctor_type');
        }

        return array(
            'edit_form' => $editForm->createView(),
        );
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}", name="cms_online_doctor_type_delete", requirements={"id" = "\d+"})
     * @Method("GET")
     */
    public function deleteAction(Request $request, OnlineDoctorType $question)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($question);
        $em->flush();

        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Төрөл амжилттай устлаа!');
        return $this->redirectToRoute('cms_online_doctor_type');
    }

}
