<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\Content;
use happy\CmsBundle\Entity\Medicals;
use happy\WebBundle\Form\AdviceSearchType;
use happy\WebBundle\Util\EmailBox;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('@happyWeb/Default/comingsoon.html.twig');
    }

    /**
     * @Route("/home", name="home_page")
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');

        /**@var Medicals[] $medical */
        $medical = $qb
            ->orderBy('n.createdDate', 'desc')
            ->addOrderBy('n.isOntsloh', 'desc')
            ->where('n.isOntsloh = 1')
            ->andWhere('n.isRemove = 0')
            ->getQuery()
            ->getArrayResult();


        /**@var Content[] $advice */
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');
        $advice = $qb
            ->orderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Default/home.html.twig', array(
            'id' => 1,
            'medical' => $medical,
            'advice' => $advice
        ));
    }

    /**
     * @Route("/email", name="email")
     * @Method({"GET", "POST"})
     * Төслүүд
     *
     */
    public function emailsAction(Request $request)
    {
        $email = new EmailBox($this->container);
        $email->sendEmail('Hi', 'gombo29@gmail.com', 'hi');

        var_dump('success');
        exit();
    }


}
