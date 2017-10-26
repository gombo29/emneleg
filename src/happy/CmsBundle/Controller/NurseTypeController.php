<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * DoctorType controller.
 *
 * @Route("/nurse-type")
 */
class NurseTypeController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/", name="cms_nurse_type_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorType')->createQueryBuilder('n');
        /**@var DoctorType[] $nurseType */
        $nurseType = $qb
            ->orderBy('n.createdDate', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyCms/NurseType/index.html.twig', array(
            'nursetype' => $nurseType
        ));
    }

    /**
     * Creates a new NursePosition entity.
     *
     * @Route("/new", name="cms_nurse_type_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $doctorType = new DoctorType();
        $form = $this->createForm('happy\CmsBundle\Form\DoctorTypeType', $doctorType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($doctorType);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Сувилагчийн үйлчилгээний төрөл амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_nurse_type_index');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_nurse_type_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, DoctorType $doctorType)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\DoctorTypeType', $doctorType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Сувилагчийн үйлчилгээний төрөл амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_nurse_type_index');
        }

        return array(
            'edit_form' => $editForm->createView(),
        );
    }
}
