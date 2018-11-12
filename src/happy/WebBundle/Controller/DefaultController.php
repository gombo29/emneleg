<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\Banner;
use happy\CmsBundle\Entity\Content;
use happy\CmsBundle\Entity\LaboratoryType;
use happy\CmsBundle\Entity\Medicals;
use happy\CmsBundle\Entity\MedicalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

//    /**
//     * @Route("/")
//     */
//    public function indexAction()
//    {
//        return $this->render('@happyWeb/Default/comingsoon.html.twig');
//    }

    /**
     * @Route("/phonenumber")
     */
    public function phonenumbetAction()
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Phonenumber')->createQueryBuilder('u');
        $data = $qb
            ->select('u.phoneNumber')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Default/phonenumber.html.twig', array(
            'data' => $data,
        ));
    }

    /**
     * @Route("/", name="home_page")
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

        $qb = $em->getRepository('happyCmsBundle:Banner')->createQueryBuilder('n');
        /**@var Banner[] $banner */
        $banner = $qb
            ->where('n.publishDate < :now')
            ->andWhere('n.endDate > :now')
            ->setParameter('now', new \DateTime('now'))
            ->getQuery()
            ->getArrayResult();


        /**@var Content[] $advice */
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');
        $advice = $qb
            ->orderBy('n.id', 'desc')
            ->andWhere("n.isOntsloh = 1")
            ->setMaxResults(15)
            ->getQuery()
            ->getArrayResult();


        $qb = $em->getRepository('happyCmsBundle:LaboratoryType')->createQueryBuilder('n');

        /**@var LaboratoryType[] $labType */
        $labType = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();


        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');

        $nurse = $qb
            ->orderBy('n.createdDate', 'asc')
            ->orderBy('n.like', 'desc')
            ->andWhere('n.isDoctor = 0')
            ->andWhere('n.isShow = 1')
            ->setMaxResults(2)
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:MedicalType')->createQueryBuilder('n');
        /**@var MedicalType[] $medType */
        $medType = $qb
            ->select('n.id, n.name, n.img, n.imgActive')
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Default/home.html.twig', array(
            'id' => 1,
            'medical' => $medical,
            'labType' => $labType,
            'advice' => $advice,
            'viewType' => 1,
            'nurse' => $nurse,
            'medType' => $medType,
            'banner' => $banner,

        ));
    }
}
