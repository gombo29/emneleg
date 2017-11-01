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
            ->leftJoin('n.parent', 'p')
            ->addSelect('p')
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

        $arrTree = $this->buildTree($questions);
//        var_dump($arrTree);
//        exit();

        return $this->render('@happyCms/OnlineDoctor/index.html.twig', array(
            'questions' => $arrTree,
            'typeid' => $id,
            'typename' => $typename,
        ));
    }

    public function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent']['id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
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
        $question = new OnlineDoctorQuestion();
        $form = $this->createForm('happy\CmsBundle\Form\OnlineDoctorQuestionType', $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $parent = $request->get('parentid');
            $em = $this->getDoctrine()->getManager();
            $question->setType($em->getReference('happyCmsBundle:OnlineDoctorType', $typeid));

            if($parent)
            {
                $question->setParent($em->getReference('happyCmsBundle:OnlineDoctorQuestion', $parent));
            }
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
            return $this->redirectToRoute('cms_online_doctor', array('id' => $typeid));
        }

        return array(
            'edit_form' => $editForm->createView(),
            'typeid' => $typeid
        );
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}/{typeid}", name="cms_online_doctor_delete", requirements={"id" = "\d+", "typeid" = "\d+"})
     * @Method("GET")
     */
    public function deleteAction(Request $request, OnlineDoctorQuestion $question, $typeid)
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
