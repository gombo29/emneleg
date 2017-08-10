<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Banner;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Banner controller.
 *
 * @Route("/banner")
 */
class BannerController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{page}", name="cms_banner_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;

        $searchEntity = new Banner();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\BannerSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Banner')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('n.name like :name')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }

            if ($searchForm->get('ehlehDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate > :ehlehDate')
                    ->setParameter('ehlehDate', $searchEntity->getEhlehDate());
            }

            if ($searchForm->get('duusahDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate < :duusahDate')
                    ->setParameter('duusahDate', $searchEntity->getDuusahDate());
            }
        }

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        /**@var Banner[] $content */
        $banner = $qb
            ->leftJoin('n.bannerbairlal', 'b')
            ->addSelect('b')
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();
        $currentRoute = $request->getUri();

        return $this->render('happyCmsBundle:Banner:index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'search' => $search,
            'banner' => $banner,
            'currentRoute' => $currentRoute,
            'searchform' => $searchForm->createView(),
        ));
    }


    /**
     * Creates a new banner entity.
     *
     * @Route("/new", name="cms_banner_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $currentRoute = $request->get('currentRoute');
        $banner = new Banner();
        $form = $this->createForm('happy\CmsBundle\Form\BannerType', $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $banner->uploadImage($this->container);
            $em->persist($banner);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Мэдээ амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_banner_show', array('id' => $banner->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'content' => $banner,
            'form' => $form->createView(),
        );
    }


    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_banner_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Banner $banner)
    {
        $currentRoute = $request->get('currentRoute');
        $deleteForm = $this->createDeleteForm($banner);
        $editForm = $this->createForm('happy\CmsBundle\Form\BannerType', $banner);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $banner->uploadImage($this->container);
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Мэдээ амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_banner_update', array('id' => $banner->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'img' => $banner->getImg(),
            'id' => $banner->getId(),
            'banner' => $banner,
            'currentRoute' => $currentRoute,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Show banner entity.
     *
     * @Route("/show/{id}", name="cms_banner_show" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request, Banner $banner)
    {
        $deleteForm = $this->createDeleteForm($banner);
        $currentRoute = $request->get('currentRoute');
        return array(
            'banner' => $banner,
            'currentRoute' => $currentRoute,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to delete a banner entity.
     *
     * @param Banner $banner The banner entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Banner $banner)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cms_banner_delete', array('id' => $banner->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}", name="cms_banner_delete", requirements={"id" = "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Banner $banner)
    {
        $form = $this->createDeleteForm($banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($banner);
            $em->flush();
        }
        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Banner амжилттай устлаа!');
        return $this->redirectToRoute('cms_banner_index');
    }
}
