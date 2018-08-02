<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorLog;
use happy\CmsBundle\Entity\QpayInvoice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/nurse-log")
 */
class DoctorLogController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{page}", name="cms_nurse_log_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;


        $searchEntity = new QpayInvoice();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\NurserLogSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:QpayInvoice')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getPhoneNumber() && $searchEntity->getPhoneNumber() != '') {
                $qb->andWhere('n.phoneNumber like :name')
                    ->setParameter('name', '%' . $searchEntity->getPhoneNumber() . '%');
            }
        }


        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();

        /**@var DoctorLog[] $doctorLog */
        $doctorLog = $qb
            ->leftJoin('n.doctorType', 'dt')
            ->addSelect('dt')
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyCmsBundle:DoctorLog:index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'doctorlog' => $doctorLog,
            'search' => $search,
            'searchform' => $searchForm->createView(),
            'pagesize' => $pagesize,
        ));
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/check/{id}/{pid}", name="cms_nurse_log_check", requirements={"id" = "\d+","pid" = "\d+"})
     * @Method("GET")
     */
    public function checkAction(Request $request, DoctorLog $nurseLog, $pid)
    {

        $em = $this->getDoctrine()->getManager();
        $nurseLog->setIsConfirm(1);
        $em->persist($nurseLog);
        $em->flush();

        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Сувилагчийн дуудлага баталгаажуулалт амжилттай боллоо!');

        return $this->redirectToRoute('cms_nurse_log_index', array('page' => $pid));
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/back/{id}/{pid}", name="cms_nurse_log_back", requirements={"id" = "\d+","pid" = "\d+"})
     * @Method("GET")
     */
    public function backAction(Request $request, DoctorLog $nurseLog, $pid)
    {

        $em = $this->getDoctrine()->getManager();
        $nurseLog->setIsBack(1);
        $em->persist($nurseLog);
        $em->flush();

        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Сувилагчийн дуудлага баталгаажуулалт буцаалт амжилттай боллоо!');

        return $this->redirectToRoute('cms_nurse_log_index', array('page' => $pid));
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/text/{id}/{pid}", name="cms_nurse_log_text", requirements={"id" = "\d+","pid" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function changetextAction(Request $request, DoctorLog $nurseLog, $pid)
    {
        $text = $request->request->get('text');
        $em = $this->getDoctrine()->getManager();
        $nurseLog->setDescr($text);
        $em->persist($nurseLog);
        $em->flush();

        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Сувилагчийн дуудлага баталгаажуулалт буцаалт амжилттай боллоо!');

        return $this->redirectToRoute('cms_nurse_log_index', array('page' => $pid));
    }

}
