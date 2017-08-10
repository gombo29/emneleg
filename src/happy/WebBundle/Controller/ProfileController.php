<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\Notification;
use happy\CmsBundle\Entity\Project;
use happy\CmsBundle\Entity\ProjectInvestor;
use happy\CmsBundle\Entity\ProjectTeam;
use happy\CmsBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function indexAction(Request $request)
    {
        if ($this->getUser()) {

            $t = $request->get('t');

            $user = $this->container->get('security.context')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();

            $user->setIsNoti(0);
            $em->persist($user);
            $em->flush();

            $userForm = $this->createForm('happy\WebBundle\Form\UserType', $user);
            $userForm->handleRequest($request);

            if ($userForm->isSubmitted() && $userForm->isValid()) {
                $user->uploadImage($this->container);
                $em->flush();
                return $this->redirectToRoute('profile');
            }

            /**@var Notification[] $not */
            $qb = $em->getRepository('happyCmsBundle:Notification')->createQueryBuilder('n');
            $not = $qb
                ->where('n.user = :id')
                ->setParameter('id', $user->getId())
                ->getQuery()
                ->getArrayResult();

            $projectInt = array();
            $investor = array();

            if ($user->getIsInvestor() == true) {
                $data = 1;

                /**@var Project[] $project */
                $qb = $em->getRepository('happyCmsBundle:ProjectInvestor')->createQueryBuilder('n');
                $proj = $qb
                    ->leftJoin('n.project', 'p')
                    ->addSelect('p')
                    ->leftJoin('p.user', 'u')
                    ->addSelect('u')
                    ->where('n.user = :id')
                    ->setParameter('id', $user->getId())
                    ->andWhere('n.isInvestment = 1')
                    ->getQuery()
                    ->getArrayResult();

                if ($proj) {
                    foreach ($proj as $key => $p) {
                        $project[$key] = $proj[$key]['project'];
                    }
                }

                /**@var Project[] $project */
                $qb = $em->getRepository('happyCmsBundle:ProjectInvestor')->createQueryBuilder('n');
                $proj = $qb
                    ->leftJoin('n.project', 'p')
                    ->addSelect('p')
                    ->leftJoin('p.user', 'u')
                    ->addSelect('u')
                    ->where('n.user = :id')
                    ->setParameter('id', $user->getId())
                    ->andWhere('n.isInterest = 1')
                    ->getQuery()
                    ->getArrayResult();

                if ($proj) {
                    foreach ($proj as $key => $p) {
                        $projectInt[$key] = $proj[$key]['project'];
                    }
                }


            } else {
                $data = 0;

                /**@var Project[] $project */
                $qb = $em->getRepository('happyCmsBundle:Project')->createQueryBuilder('n');
                $project = $qb
                    ->leftJoin('n.user', 'u')
                    ->addSelect('u')
                    ->where('n.user = :id')
                    ->setParameter('id', $user->getId())
                    ->andWhere('n.isRemove = 0')
                    ->getQuery()
                    ->getArrayResult();

                $ids = array_map(function ($n) {
                    return $n['id'];
                }, $project);


                /**@var ProjectInvestor[] $investor */
                $qb = $em->getRepository('happyCmsBundle:ProjectInvestor')->createQueryBuilder('n');
                $investor = $qb
                    ->leftJoin('n.user', 'u')
                    ->addSelect('u')
                    ->where($qb->expr()->in('n.project', ':p1'))
                    ->setParameter('p1', $ids)
                    ->distinct()
                    ->getQuery()
                    ->getArrayResult();

            }

        } else {
            return $this->redirectToRoute('happy_web_default_index');
        }


        return $this->render('happyWebBundle:Profile:profile.html.twig', array(
            'id' => 0,
            'project' => $project,
            'userForm' => $userForm->createView(),
            'user' => $this->getUser(),
            'data' => $data,
            'projectInt' => $projectInt,
            'noti' => $not,
            't' => $t,
            'investor' => $investor,

        ));
    }

    /**
     * Creates a new project entity.
     *
     * @Route("/project/new", name="project_new")
     * @Method({"GET", "POST"})
     *
     */
    public function newAction(Request $request)
    {
        /**@var Project[] $project */
        $project = new Project();
        $form = $this->createForm('happy\WebBundle\Form\ProjectType', $project);
        $form->handleRequest($request);
        if ($this->getUser()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $this->container->get('security.context')->getToken()->getUser();
                $em = $this->getDoctrine()->getManager();
                $project->setIsHide(1);
                $project->setUser($user);
                $em->persist($project);
                $em->flush();
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('success', 'Агуулга амжилттай үүлслээ!');

                return $this->redirectToRoute('project_update', array('id' => $project->getId(), 't' => 2));
            }
        }

        return $this->render('happyWebBundle:Profile:projectnew.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * Remove Project entity.
     *
     * @Route("/project/delete/{id}", name="project_delete" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function deleteAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$project) {
            throw $this->createNotFoundException('Өгөгдөл олдсонгүй.');
        }
        $project->setIsRemove(1);
        $em->flush();

        return $this->redirectToRoute('profile');
    }

    /**
     * Remove Notification entity.
     *
     * @Route("/noti/delete/{id}", name="noti_delete" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function notideleteAction(Notification $noti)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$noti) {
            throw $this->createNotFoundException('Өгөгдөл олдсонгүй.');
        }
        $em->remove($noti);
        $em->flush();

        return $this->redirectToRoute('profile');
    }


    /**
     * Updates project entity.
     *
     * @Route("/project/update/{id}", name="project_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Project $project)
    {

        $type = $request->get('t');
        $editForm = $this->createForm('happy\WebBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        $dealForm = $this->createForm('happy\WebBundle\Form\ProjectDealType', $project);
        $dealForm->handleRequest($request);

        $team = new ProjectTeam();
        $teamForm = $this->createForm('happy\WebBundle\Form\ProjectTeamType', $team);
        $teamForm->handleRequest($request);

        $imageForm = $this->createForm('happy\WebBundle\Form\ProjectImageType', $project);
        $imageForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->flush();
            return $this->redirectToRoute('project_update', array('id' => $project->getId(), 't' => 1));
        }


        if ($dealForm->isSubmitted() && $dealForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('project_update', array('id' => $project->getId(), 't' => 2));
        }

        if ($teamForm->isSubmitted() && $teamForm->isValid()) {
            $team->setProject($project);
            $team->uploadImage($this->container);
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('project_update', array('id' => $project->getId(), 't' => 3));
        }

        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $project->uploadImage($this->container);
            $project->uploadFile($this->container);
            $project->uploadProfileImage($this->container);
            $em->flush();
            return $this->redirectToRoute('project_update', array('id' => $project->getId(), 't' => 4));
        }

        $qb = $em->getRepository('happyCmsBundle:ProjectTeam')->createQueryBuilder('n');
        /**@var ProjectTeam[] $project_team */
        $project_team = $qb
            ->where('n.project = :id')
            ->setParameter('id', $project->getId())
            ->orderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();


        return $this->render('happyWebBundle:Profile:projectupdate.html.twig', array(
            'main_form' => $editForm->createView(),
            'dealForm' => $dealForm->createView(),
            'teamForm' => $teamForm->createView(),
            'imageForm' => $imageForm->createView(),
            'project' => $project,
            'type' => $type,
            'project_team' => $project_team,
        ));
    }


    /**
     * Remove ProjectTeam entity.
     *
     * @Route("/project_team/delete/{id}", name="project_team_delete" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function teamDeleteAction(Request $request, ProjectTeam $team)
    {
        $id = $request->get('project');
        $em = $this->getDoctrine()->getManager();

        if (!$team) {
            throw $this->createNotFoundException('Өгөгдөл олдсонгүй.');
        }
        $em->remove($team);
        $em->flush();
        return $this->redirectToRoute('project_update', array('id' => $id, 't' => 3));
    }


    /**
     * Updates project team entity.
     *
     * @Route("/project_team/update/{id}", name="project_team_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function teamupdateAction(Request $request, ProjectTeam $team)
    {

        $teamForm = $this->createForm('happy\WebBundle\Form\ProjectTeamType', $team);
        $teamForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($teamForm->isSubmitted() && $teamForm->isValid()) {
            $team->uploadImage($this->container);
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('project_update', array('id' => $team->getProject()->getId(), 't' => 3));
        }

        return $this->render('happyWebBundle:Profile:project_team_update.html.twig', array(
            'teamForm' => $teamForm->createView(),
            'team' => $team
        ));
    }
}
