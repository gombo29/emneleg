<?php

namespace happy\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {


        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:User')->createQueryBuilder('n');
        $user = $qb
            ->getQuery()
            ->getArrayResult();

        $arr = array();

        foreach ($user as $key => $u) {
            if ($key != 0) {
                array_push($arr, $u['username']);
            }
        }

        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');
        $medical = $qb
            ->where($qb->expr()->in('n.who' , ':ids' ))
            ->setParameter('ids', $arr)
            ->getQuery()
            ->getArrayResult();
        return $this->render('happyCmsBundle:Default:index.html.twig' ,array(
            "medical" => $medical,
            "user" => $user
        ));
    }
}
