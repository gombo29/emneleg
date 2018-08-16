<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Banner;
use happy\CmsBundle\Entity\Phonenumber;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Banner controller.
 *
 * @Route("/phone")
 */
class PhoneController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/", name="cms_phone_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Phonenumber')->createQueryBuilder('n');

        /**@var Phonenumber[] $pn */
        $pn = $qb
            ->getQuery()
            ->getArrayResult();

        $currentRoute = $request->getUri();

        return $this->render('happyCmsBundle:Phone:index.html.twig', array(
            'pm' => $pn,
            'currentRoute' => $currentRoute,
        ));
    }


    /**
     * Creates a new banner entity.
     *
     * @Route("/new", name="cms_phone_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $currentRoute = $request->get('currentRoute');
        $banner = new Phonenumber();
        $form = $this->createForm('happy\CmsBundle\Form\PhonenumberType', $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($banner);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Утасны дугаар амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_phone_index', array('id' => $banner->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'content' => $banner,
            'form' => $form->createView(),
        );
    }


    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_phone_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Phonenumber $banner)
    {
        $currentRoute = $request->get('currentRoute');
        $editForm = $this->createForm('happy\CmsBundle\Form\PhonenumberType', $banner);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Утасны дугаар амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_phone_index', array('id' => $banner->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'id' => $banner->getId(),
            'banner' => $banner,
            'currentRoute' => $currentRoute,
            'edit_form' => $editForm->createView(),
        );
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}", name="cms_phone_delete", requirements={"id" = "\d+"})
     * @Method("GET")
     */
    public function deleteAction(Request $request, Phonenumber $banner)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($banner);
        $em->flush();
        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Утасны дугаар амжилттай устлаа!');
        return $this->redirectToRoute('cms_phone_index');
    }
}
