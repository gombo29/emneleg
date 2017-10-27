<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorQpay;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/nurse-qpay")
 */
class DoctorQpayController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{id}", name="cms_nurse_qpay_index", requirements={"id" = "\d+"} )
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorQpay')->createQueryBuilder('n');
        /**@var DoctorQpay[] $nurseQr */
        $nurseQr = $qb
            ->orderBy('n.createdDate', 'desc')
            ->where('n.doctor = :id')
            ->leftJoin('n.doctorType', 'dt')
            ->addSelect('dt')
            ->leftJoin('n.doctorPosition', 'dp')
            ->addSelect('dp')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');
        /**@var DoctorQpay[] $nurseQr */
        $nurse = $qb
            ->where('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyCms/DoctorQpay/index.html.twig', array(
            'nurseqr' => $nurseQr,
            'nursename' => $nurse[0]['name'],
            'id' => $id
        ));
    }

    /**
     * Creates a new NursePosition entity.
     *
     * @Route("/new/{id}", name="cms_nurse_qr_new", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request, $id)
    {
        $doctorQPAY = new DoctorQpay();
        $form = $this->createForm('happy\CmsBundle\Form\DoctorQRType', $doctorQPAY);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $doctorQPAY->uploadImage($this->container);
            $doctorQPAY->setDoctor($em->getReference('happyCmsBundle:Doctors', $id));
            $doctorQPAY->setName('null');
            $em->persist($doctorQPAY);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Сувилагчийн QR амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_nurse_qpay_index', array('id' => $id));
        }

        return array(
            'form' => $form->createView(),
            'id' => $id
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}/{nid}", name="cms_nurse_qpay_update" , requirements={"id" = "\d+","nid" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, DoctorQpay $nurseQPAY, $nid)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\DoctorQRType', $nurseQPAY);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();
            $nurseQPAY->uploadImage($this->container);
            $em->persist($nurseQPAY);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Сувилагчийн QR амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_nurse_qpay_index', array('id' => $nid));
        }

        return array(
            'edit_form' => $editForm->createView(),
            'nid' => $nid,
            'img' => $nurseQPAY->getPhoto()
        );
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}/{nid}", name="cms_nurse_qpay_delete", requirements={"id" = "\d+","nid" = "\d+"})
     * @Method("GET")
     */
    public function deleteAction(Request $request, DoctorQpay $nurseQPAY, $nid)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($nurseQPAY);
        $em->flush();

        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Сувилагчийн QR амжилттай устлаа!');
        return $this->redirectToRoute('cms_nurse_qpay_index', array('id' => $nid));
    }
}
