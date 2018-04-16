<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorQpay;
use happy\CmsBundle\Entity\OnlineDoctorQuestion;
use happy\CmsBundle\Entity\OnlineDoctorType;
use happy\CmsBundle\Form\OnlineDoctorQuestionType;
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
     * @Route("/{id}", name="cms_online_doctor", requirements={"id" = "\d+"} )
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:OnlineDoctorQuestion')->createQueryBuilder('n');
        /**@var DoctorQpay[] $nurseQr */
        $questions = $qb
            ->leftJoin('n.type', 't')
            ->addSelect('t')
            ->leftJoin('n.childYes', 'yes')
            ->addSelect('yes')
            ->leftJoin('n.childNo', 'no')
            ->addSelect('no')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();

        $typename = null;

        if ($questions == null) {
            $qb = $em->getRepository('happyCmsBundle:OnlineDoctorType')->createQueryBuilder('n');
            /**@var OnlineDoctorType[] $type */
            $type = $qb
                ->select('n.name')
                ->where('n.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult();

            $typename = $type[0]['name'];

        } else {
            foreach ($questions as $key => $item) {
                if ($key == 0) {
                    $typename = $item['type']['name'];
                }
                break;
            }
        }

        return $this->render('@happyCms/OnlineDoctor/index.html.twig', array(
            'questions' => $questions,
            'typeid' => $id,
            'typename' => $typename,
        ));
    }

    /**
     * Creates a new NursePosition entity.
     *
     * @Route("/new/{typeid}", name="cms_online_doctor_new" ,  requirements={"typeid" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request, $typeid)
    {
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('happyCmsBundle:OnlineDoctorType')->find($typeid);
        $question = new OnlineDoctorQuestion();
        $form = $this->createForm(new OnlineDoctorQuestionType($typeid, $type->getParent()->getId()), $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $parent = $request->get('parentid');

            $question->setType($em->getReference('happyCmsBundle:OnlineDoctorType', $typeid));

            if ($parent) {
                $question->setParent($em->getReference('happyCmsBundle:OnlineDoctorQuestion', $parent));
            }

            $question->uploadImage($this->container);
            $question->uploadImage2($this->container);

            $em->persist($question);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Асуулт амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_online_doctor', array('id' => $typeid));
        }

        return array(
            'form' => $form->createView(),
            'typeid' => $typeid
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}/{typeid}", name="cms_online_doctor_update" , requirements={"id" = "\d+", "typeid" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, OnlineDoctorQuestion $question, $typeid)
    {
        $editForm = $this->createForm(new OnlineDoctorQuestionType($typeid, $question->getType()->getParent()->getId()), $question);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $question->uploadImage($this->container);
            $question->uploadImage2($this->container);
            $em->persist($question);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Асуулт амжилттай засагдлаа!');
            return $this->redirectToRoute('cms_online_doctor_update', array('id' => $question->getId(), 'typeid' => $typeid));
        }

        return array(
            'edit_form' => $editForm->createView(),
            'typeid' => $typeid,
            'question' => $question
        );
    }


    /**
     * Updates banner entity.
     *
     * @Route("/update-photo/{id}/{typeid}", name="cms_online_doctor_update_photo" , requirements={"id" = "\d+", "typeid" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function removeImgAction(Request $request, OnlineDoctorQuestion $question, $typeid)
    {

        $em = $this->getDoctrine()->getManager();
        $question->setPhoto(null);
        $em->persist($question);
        $em->flush();
        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Зураг амжилттай устлаа!');

        return $this->redirectToRoute('cms_online_doctor_update', array('id' => $question->getId(), 'typeid' => $typeid));
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update-photo-two/{id}/{typeid}", name="cms_online_doctor_update_photo_two" , requirements={"id" = "\d+", "typeid" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function removeImg2Action(Request $request, OnlineDoctorQuestion $question, $typeid)
    {

        $em = $this->getDoctrine()->getManager();
        $question->setPhoto2(null);
        $em->persist($question);
        $em->flush();
        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Зураг амжилттай устлаа!');

        return $this->redirectToRoute('cms_online_doctor_update', array('id' => $question->getId(), 'typeid' => $typeid));
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}/{typeid}", name="cms_online_doctor_delete", requirements={"id" = "\d+", "typeid" = "\d+"})
     * @Method("GET")
     */
    public
    function deleteAction(Request $request, OnlineDoctorQuestion $question, $typeid)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($question);
        $em->flush();

        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Асуулт амжилттай устлаа!');
        return $this->redirectToRoute('cms_online_doctor', array('id' => $typeid));
    }

}
