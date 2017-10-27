<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\DoctorPosition;
use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\DoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/nurse")
 */
class NurseController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{page}", name="web_nurse_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');
        /**@var Doctors[] $nurse */
        $nurse = $qb
            ->andWhere('n.isDoctor = 0')
            ->andWhere('n.isShow = 1')
            ->orderBy('n.createdDate', 'asc')
            ->addOrderBy('n.star', 'desc')
            ->getQuery()
            ->getArrayResult();

        $positionIds = $request->get('position');
        $serviceIds = $request->get('services');

        $qb = $em->getRepository('happyCmsBundle:DoctorType')->createQueryBuilder('n');
        /**@var DoctorType[] $nurseType */
        $nurseType = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:DoctorPosition')->createQueryBuilder('n');
        /**@var DoctorPosition[] $nursePosition */
        $nursePosition = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Nurse/index.html.twig', array(
            'nurse' => $nurse,
            'nurseService' => $nurseType,
            'nursePosition' => $nursePosition,
            'positionIds' => $positionIds,
            'serviceIds' => $serviceIds,
        ));
    }



//    /**
//     *  Lists all content entities.
//     *
//     * @Route("/{page}", name="web_nurse_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
//     * @Method("GET")
//     * @Template()
//     *
//     */
//    public function indexAction(Request $request, $page)
//    {
//        $pagesize = 20;
//
//        $searchEntity = new Doctors();
//        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\NurseSearchType', $searchEntity);
//        $search = false;
//
//        if ($request->get("submit") == 'submit') {
//            $searchForm->bind($request);
//            $search = true;
//        }
//
//        $em = $this->getDoctrine()->getManager();
//        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');
//
//        if ($search) {
//
//        }
//        $qb
//            ->andWhere('n.isDoctor = 0')
//            ->andWhere('n.isShow = 1');
//
//
//        $countQueryBuilder = clone $qb;
//        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
//        /**@var Doctors[] $nurse */
//        $nurse = $qb
//            ->orderBy('n.createdDate', 'asc')
//            ->orderBy('n.star', 'desc')
//            ->setFirstResult(($page - 1) * $pagesize)
//            ->setMaxResults($pagesize)
//            ->getQuery()
//            ->getArrayResult();
//
//        return $this->render('@happyWeb/Nurse/index.html.twig', array(
//            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
//            'count' => $count,
//            'page' => $page,
//            'search' => $search,
//            'searchform' => $searchForm->createView(),
//            'nurse' => $nurse
//        ));
//    }

    /**
     * Updates doctor entity.
     *
     * @Route("/register", name="cms_nurse_register" )
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $nurse = new Doctors();
        $form = $this->createForm('happy\WebBundle\Form\NurseType', $nurse);
        $form->handleRequest($request);
        $s = $request->get('s');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $nurse->uploadImage($this->container);
            $nurse->setIsDoctor(0);
            $em->persist($nurse);
            $em->flush();
            return $this->redirectToRoute('cms_nurse_register', array('s' => 1));
        }

        return array(
            'nurse' => $nurse,
            'form' => $form->createView(),
            's' => $s
        );
    }
}
