<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\MedicalLabType;
use happy\CmsBundle\Entity\MedicalMedType;
use happy\CmsBundle\Entity\MedicalPhoto;
use happy\CmsBundle\Entity\Medicals;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Medical controller.
 *
 * @Route("/medical")
 */
class MedicalController extends Controller
{
    /**
     *  Lists all project entities.
     *
     * @Route("/list/{page}", name="api_medical_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 10;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        /**@var Medicals[] $medical */
        $medical = $qb
            ->select('n.id', 'n.name', 'n.headline', 'n.phone', 'n.photo', 'n.isParking', 'n.isCard', 'n.isWifi', 'n.isTasag')
            ->orderBy('n.createdDate', 'desc')
            ->addOrderBy('n.isOntsloh', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();


        return new JsonResponse(
            array(
                "medical" => $medical,
                "pagesize" => $pagesize,
                'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
                'count' => $count,
                'page' => $page)
        );
    }

    /**
     * Remove Medical entity.
     *
     * @Route("/detail/{id}", name="api_medical_detail" , requirements={"id" = "\d+"})
     *
     *
     */
    public function medicalDetailAction(Medicals $medical)
    {
        $arr = array();

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:MedicalPhoto')->createQueryBuilder('n');
        $medicalPhoto = $qb
            ->where('n.medical = :medid')
            ->setParameter('medid', $medical)
            ->orderBy('n.sortId', 'asc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');
        $generalinfo = $qb
            ->where('n.id = :medid')
            ->setParameter('medid', $medical)
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('n');
        $medicalLabType = $qb
            ->leftJoin('n.labType', 'mt')
            ->addSelect("mt")
            ->where('n.medical = :medid')
            ->setParameter('medid', $medical)
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');
        $medicalDoctor = $qb
            ->where('n.medical = :medid')
            ->setParameter('medid', $medical)
            ->getQuery()
            ->getArrayResult();

        $tasagInfo = json_decode($medical->getTasagInfo());

        return new JsonResponse(
            array(
                'generalinfo' => $generalinfo,
                'medicalphotos' => $medicalPhoto,
                'medicalLabType' => $medicalLabType,
                'medicalDoctor' => $medicalDoctor,
                'tasagInfo' => $tasagInfo
            )
        );


    }
}