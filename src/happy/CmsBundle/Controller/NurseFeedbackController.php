<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\DoctorFeedback;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/nurse-feedback")
 */
class NurseFeedbackController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{page}", name="cms_nurse_feedback_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;


        $searchEntity = new DoctorFeedback();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\DoctorFeedbackSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorFeedback')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getPhoneNumber() && $searchEntity->getPhoneNumber() != '') {
                $qb->andWhere('n.phoneNumber like :name')
                    ->setParameter('name', '%' . $searchEntity->getPhoneNumber() . '%');
            }
        }

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        /**@var DoctorFeedback[] $nurseFeedback */
        $nurseFeedback = $qb
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyCms/NurseFeedback/index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'feedback' => $nurseFeedback,
            'search' => $search,
            'searchform' => $searchForm->createView(),
        ));
    }

}
