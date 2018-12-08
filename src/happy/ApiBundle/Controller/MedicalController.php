<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\MedicalLabType;
use happy\CmsBundle\Entity\MedicalMedType;
use happy\CmsBundle\Entity\MedicalPhoto;
use happy\CmsBundle\Entity\Medicals;
use happy\CmsBundle\Entity\MedicalType;
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

    /* ======= APP HOME ======= */

    /**
     *  Lists all project entities.
     *
     * @Route("/special", name="api_medical_special")
     * @Method("GET")
     *
     */
    public function specialAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');

        /**@var Medicals[] $medical */
        $medical = $qb
            ->select('n.id', 'n.name', 'n.phone', 'n.photo')
            ->addOrderBy('n.isOntsloh', 'desc')
            ->orderBy('n.createdDate', 'asc')
            ->where('n.isOntsloh = 1')
//            ->setMaxResults(6)
            ->getQuery()
            ->getArrayResult();

        foreach ($medical as $key => $m) {
            $phones = explode(";", $m['phone']);
            $medical[$key]['phone'] = $phones[0];
        }

        return new JsonResponse(
            array(
                "medical" => $medical,
            )
        );
    }


    /**
     *  Lists all project entities.
     *
     * @Route("/medical-type", name="api_medical_type")
     * @Method("GET")
     *
     */
    public function medTypeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('happyCmsBundle:MedicalType')->createQueryBuilder('n');
        /**@var MedicalType[] $medType */
        $medType = $qb
            ->select('n.id, n.name', 'n.imgMobile as img')
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();


        return new JsonResponse(
            array(
                "medicalType" => $medType,
            )
        );
    }

    /* ======= APP HOSPITAL FILTER ======= */

    /**
     *  Lists all project entities.
     *
     * @Route("/lab-type", name="api_lab_type")
     * @Method("GET")
     *
     */
    public function medLabTypeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('happyCmsBundle:LaboratoryType')->createQueryBuilder('n');
        /**@var MedicalType[] $medType */
        $labType = $qb
            ->select('n.id, n.name')
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();


        return new JsonResponse(
            array(
                "labType" => $labType,
            )
        );
    }

    /* ======= APP HOSPITAL LIST ======= */

    /**
     *  Lists all project entities.
     *
     * @Route("/list/{page}/{typeId}", name="api_medical_list", requirements={"page" = "\d+" , "typeId" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     *
     */
    public function indexAction(Request $request, $page, $typeId)
    {
        $pagesize = 10;
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');
        $keyword = $request->get('name');
        $qbMedType = $em->getRepository('happyCmsBundle:MedicalMedType')->createQueryBuilder('n');
        $labIds = $request->get('labIds');

        $medicalMedIds = $qbMedType
            ->select('m.id')
            ->leftJoin('n.medical', 'm')
            ->where($qbMedType->expr()->in('n.medicalType', ':p1'))
            ->setParameter('p1', $typeId)
            ->groupBy('m.id')
            ->getQuery()
            ->getArrayResult();
        
        
     
          if ($labIds == null) {
              $labIds = [1,13];
          }

        if ($medicalMedIds != null) {
           if ($labIds != null) {
                $qblab = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('n');
                $medicalMedIds = $qblab
                    ->select('m.id')
                    ->leftJoin('n.medical', 'm')
                    ->where($qblab->expr()->in('n.labType', ':p1'))
                    ->setParameter('p1', $labIds)
                    ->andWhere($qblab->expr()->in('n.medical', ':p2'))
                    ->setParameter('p2', $medicalMedIds)
                    ->groupBy('m.id')
                    ->having('COUNT(m.id) = :labcount')
                    ->setParameter('labcount', sizeof($labIds))
                    ->getQuery()
                    ->getArrayResult();
            }
            $qb
                ->andWhere($qb->expr()->in('n.id', ':medIds'))
                ->setParameter(':medIds', $medicalMedIds);
        }

        if ($keyword) {
            $qb
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->like('n.name', ':medName'),
                    $qb->expr()->like('n.nameLat', ':medName')
                ))
                ->setParameter('medName', '%' . $keyword . '%');
     
        }

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        /**@var Medicals[] $medical */
        $medical = $qb
            ->select('n.id', 'n.name', 'n.headline', 'n.phone', 'n.photo', 'n.isParking', 'n.isCard', 'n.isWifi', 'n.longLat')
            ->orderBy('n.isOntsloh', 'desc')
            ->addOrderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();


        foreach ($medical as $key => $m) {
            $phones = explode(";", $m['phone']);
            $medical[$key]['phone'] = $phones[0];
        }

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
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:MedicalPhoto')->createQueryBuilder('n');
        $medicalPhoto = $qb
            ->select('n.stamp_path')
            ->where('n.medical = :medid')
            ->setParameter('medid', $medical)
            ->orderBy('n.sortId', 'asc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');
        $generalinfo = $qb
            ->select('n.name', 'n.headline', 'n.address', 'n.phone', 'n.email', 'n.fbAddress',
                'n.website', 'n.busStation', 'n.isParking', 'n.likeCount',
                'n.parkingPrice', 'n.isCard', 'n.isWifi', 'n.hoolTotal', 'n.isSaturdayHool',
                'n.isSundayHool', 'n.isDaatgal', 'n.isLaboratory',
                'n.isOntsloh', 'n.isRemove', 'n.photo', 'n.timeTable', 'n.isDoctor', 'n.longLat')
            ->where('n.id = :medid')
            ->setParameter('medid', $medical)
            ->getQuery()
            ->getResult();

        foreach ($generalinfo as $key => $g) {
            if ($g['website'] == null) {
                $generalinfo[$key]['website'] = 'тодорхойгүй';
            }

            if ($g['busStation'] == null) {
                $generalinfo[$key]['busStation'] = 'тодорхойгүй';
            }

            if ($g['email'] == null) {
                $generalinfo[$key]['email'] = 'тодорхойгүй';
            }

            if ($g['fbAddress'] == null) {
                $generalinfo[$key]['fbAddress'] = 'тодорхойгүй';
            }
        }
        $qb = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('n');
        $medicalLabType = $qb
            ->select('lt.id', 'lt.name', 'n.price')
            ->leftJoin('n.labType', 'lt')
            ->where('n.medical = :medid')
            ->setParameter('medid', $medical)
            ->getQuery()
            ->getArrayResult();

        foreach ($medicalLabType as $key => $m) {
            if ($m['price'] == 0) {
                $medicalLabType[$key]['price'] = 'Тодорхойгүй';
            }
        }

        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');
        $medicalDoctor = $qb
            ->select('n.id', 'n.name', 'n.photo')
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
