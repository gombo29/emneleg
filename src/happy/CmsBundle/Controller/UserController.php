<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Project;
use happy\CmsBundle\Entity\User;
use happy\CmsBundle\Entity\UserLogs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/{page}", name="cms_user_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 30;

        $em = $this->getDoctrine()->getManager();
        $search = false;
        $searchEntity = new User ();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\UserSearchType', $searchEntity);

        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $qb = $em->getRepository('happyCmsBundle:User')->createQueryBuilder('p');


        if ($search) {
            if ($searchEntity->getUsername() && $searchEntity->getUsername() != '') {
                $qb->andWhere('p.username like :username')
                    ->setParameter('username', '%' . $searchEntity->getUsername() . '%');
            }
        }


        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(p.id)')->getQuery()->getSingleScalarResult();

        $users = $qb
            ->orderBy('p.id', 'DESC')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'user' => $users,
            'search' => $search,
            'searchform' => $searchForm->createView(),
        );
    }


    /**
     * Lists all User entities.
     *
     * @Route("/log/{page}", name="cms_user_log", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     */
    public function logAction(Request $request, $page)
    {
        $pagesize = 30;

        $em = $this->getDoctrine()->getManager();
        $search = false;
        $searchEntity = new UserLogs();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\UserLogType', $searchEntity);

        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $qb = $em->getRepository('happyCmsBundle:UserLogs')->createQueryBuilder('p');


        if ($search) {
            if ($searchEntity->getAdminname() && $searchEntity->getAdminname() != '') {
                $qb->andWhere('p.adminname like :adminname')
                    ->setParameter('adminname', '%' . $searchEntity->getAdminname() . '%');
            }
        }


        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(p.id)')->getQuery()->getSingleScalarResult();

        $users = $qb
            ->orderBy('p.id', 'DESC')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'user' => $users,
            'search' => $search,
            'searchform' => $searchForm->createView(),
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="cms_user_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('happy\CmsBundle\Form\UserType', $user, array(
            'em' => $em
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isMail = $em->getRepository("happyCmsBundle:User")->findOneBy(array(
                'email' => $user->getEmail(),
            ));
            $isUsername = $em->getRepository("happyCmsBundle:User")->findOneBy(array(
                'username' => $user->getUsername(),
            ));
            if ($isMail == null) {
                if ($isUsername == null) {
                    $user->setEnabled(true);
                    $user->addRole('ROLE_CMS_USER');
                    $em->persist($user);
                    $em->flush();

                    $request
                        ->getSession()
                        ->getFlashBag()
                        ->add('success', 'Амжилттай үүслээ!');
                    return $this->redirectToRoute('cms_user_show', array('id' => $user->getId()));
                } else {
                    $request
                        ->getSession()
                        ->getFlashBag()
                        ->add('danger', 'Нэврэх нэр бүртгэлтэй байна!');
                    return $this->redirectToRoute('cms_user_new');
                }
            } else {
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('danger', 'Цахим шуудан бүртгэлтэй байна!');
                return $this->redirectToRoute('cms_user_new');
            }
        }
        return array(
            'user' => $user,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/show/{id}", name="cms_user_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request, User $user)
    {
        $currentRoute = $request->get('currentRoute');
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('happyCmsBundle:Role')->createQueryBuilder('e')
            ->andWhere('e.name in (:roles)')
            ->setParameter('roles', $user->getRoles())
            ->getQuery()
            ->getResult();

        $form = $this->createDeleteForm($user);

        return array(
            'currentRoute' => $currentRoute,
            'user' => $user,
            'roles' => $roles,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cms_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="cms_user_delete", requirements={"id" = "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
        }

        $this->getDoctrine()->getManager()->flush();
        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Өөрчлөлт хадгалагдлаа!');

        return $this->redirectToRoute('cms_user_index', array('id' => 1));
    }

    /**
     * @Route("/check/username/{type}", name="cms_user_check_username", requirements={"type" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public
    function checkusernameAction(Request $request)
    {
        $var = 0;
        $value = $request->get('value');
        $type = $request->get('type');
        $em = $this->getDoctrine()->getManager();
        if ($type == 1) {
            $isUsername = $em->getRepository("happyCmsBundle:User")->findOneBy(array(
                'username' => $value,
            ));
            if ($isUsername == null) {
                $var = 1;
            }
        } else {
            $isMail = $em->getRepository("happyCmsBundle:User")->findOneBy(array(
                'email' => $value,
            ));
            if ($isMail == null) {
                $var = 1;
            }

        }


        return new JsonResponse(array(
            'var' => $var,
        ));
    }


    /**
     * @Route("/edit/password/{id}", name="cms_user_edit_password")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editPasswordAction(Request $request, User $user)
    {
        $currentRoute = $request->get('currentRoute');
        $editForm = $this->createForm('happy\CmsBundle\Form\UserPasswordType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updatePassword($user);

            $this->getDoctrine()->getManager()->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Өөрчлөлт хадгалагдлаа!');
            return $this->redirectToRoute('cms_user_show', array('id' => $user->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        );
    }


    /**
     * @Route("/edit/role/{id}", name="cms_user_edit_role",  requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editRoleAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();


        $editForm = $this->createForm('happy\CmsBundle\Form\UserRoleType', $user, array(
            'em' => $em
        ));

        if ($request->isMethod('POST')) {
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {

                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($user);
                $user->addRole('ROLE_CMS_USER');
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('success', 'Өөрчлөлт хадгалагдлаа!');
                return $this->redirectToRoute('cms_user_show', array('id' => $user->getId()));
            }
        }

        return array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * @Route("/edit/enable/{id}", name="cms_user_edit_enable", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editEnableAction(Request $request, User $user)
    {
        $currentRoute = $request->get('currentRoute');
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createForm('happy\CmsBundle\Form\UserEnableType', $user, array(
            'em' => $em
        ));

        if ($request->isMethod('POST')) {
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {


                $em->flush();
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('success', 'Өөрчлөлт хадгалагдлаа!');
                return $this->redirectToRoute('cms_user_show', array('id' => $user->getId(), 'currentRoute' => $currentRoute));
            }
        }

        return array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        );
    }


}