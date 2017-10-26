<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorPosition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/nurse-position")
 */
class NursePositionController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/", name="cms_nurse_position_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorPosition')->createQueryBuilder('n');
        /**@var DoctorPosition[] $nursePosition */
        $nursePosition = $qb
            ->orderBy('n.createdDate', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyCms/NursePosition/index.html.twig', array(
            'nurseposition' => $nursePosition
        ));
    }

    /**
     * Creates a new NursePosition entity.
     *
     * @Route("/new", name="cms_nurse_position_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $doctorPosition = new DoctorPosition();
        $form = $this->createForm('happy\CmsBundle\Form\DoctorPositionType', $doctorPosition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($doctorPosition);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Сувилагчийн байршил амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_nurse_position_index');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_nurse_position_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, DoctorPosition $nursePosition)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\DoctorPositionType', $nursePosition);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Сувилагчийн байршил амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_nurse_position_index');
        }

        return array(
            'edit_form' => $editForm->createView(),
        );
    }
}
