<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Doctors;
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
     * @Route("/{page}", name="cms_nurse_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;

        $searchEntity = new Doctors();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\NurseSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');

        if ($search) {

            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('n.name like :name')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }

            if ($searchForm->get('ehlehDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate > :ehlehDate')
                    ->setParameter('ehlehDate', $searchEntity->ehlehDate);
            }

            if ($searchForm->get('duusahDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate < :duusahDate')
                    ->setParameter('duusahDate', $searchEntity->duusahDate);
            }

        }

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        /**@var Doctors[] $nurse */
        $nurse = $qb
            ->orderBy('n.createdDate', 'desc')
            ->andWhere('n.isDoctor = 0')
            ->andWhere('n.isShow = 1')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyCms/Nurse/index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'search' => $search,
            'searchform' => $searchForm->createView(),
            'nurse' => $nurse
        ));
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_nurse_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Doctors $nurse)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\NurseType', $nurse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $nurse->uploadImage($this->container);
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Мэдээ амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_nurse_update', array('id' => $nurse->getId()));
        }

        return array(
            'nurse' => $nurse,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Updates nurse entity.
     *
     * @Route("/config/{id}", name="cms_nurse_config" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function configAction(Request $request, Doctors $nurse)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\NurseConfigType', $nurse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Тохиргоо амжилттай хийгдлээ!');

            return $this->redirectToRoute('cms_nurse_config', array('id' => $nurse->getId()));
        }

        return array(
            'nurse' => $nurse,
            'edit_form' => $editForm->createView(),
        );
    }


    /**
     * Show nurse entity.
     *
     * @Route("/show/{id}", name="cms_nurse_show" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request, Doctors $nurse)
    {
        return array(
            'nurse' => $nurse,
        );
    }

}
