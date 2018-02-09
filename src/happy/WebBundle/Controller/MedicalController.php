<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\LaboratoryType;
use happy\CmsBundle\Entity\MedicalMedType;
use happy\CmsBundle\Entity\Medicals;
use happy\CmsBundle\Entity\MedicalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/medicals")
 */
class MedicalController extends Controller
{
    /**
     * @Route ("/{page}/{type}", name="medicals", requirements={"page" = "\d+", "type" = "\d+"}, defaults={"page" = 1, "type" = 1} )
     * @Method({"GET", "POST"})
     * Төслүүд
     *
     */
    public function medicalsAction(Request $request, $page, $type)
    {
        // type :  1 => list ; 3 => map

        $pagesize = 20;
        $count = 0;
        $labTypeIds = $request->get('labtypes');
        $medTypeIds = $request->get('medtypes');

        $medName = $request->get('q');
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');
        $medIds = array();

        if ($medTypeIds) {
            $qbmed = $em->getRepository('happyCmsBundle:MedicalMedType')->createQueryBuilder('n');
            $medicalMedIds = $qbmed
                ->select('m.id')
                ->leftJoin('n.medical', 'm')
                ->andWhere($qbmed->expr()->in('n.medicalType', ':p2'))
                ->setParameter('p2', $medTypeIds)
                ->groupBy('m.id')
                ->getQuery()
                ->getArrayResult();

            if (sizeof($medicalMedIds) > 0) {
                foreach ($medicalMedIds as $mm) {
                    if (!in_array($mm, $medIds)) {
                        array_push($medIds, $mm);
                    }
                }
            }
        }


        if ($labTypeIds) {
            $qblab = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('n');

            $qblab
                ->select('m.id')
                ->leftJoin('n.medical', 'm')
                ->andWhere($qblab->expr()->in('n.labType', ':p1'))
                ->setParameter('p1', $labTypeIds);

            $medicalLabIds = $qblab
                ->groupBy('m.id')
                ->having('COUNT(m.id) = :labcount')
                ->setParameter('labcount', sizeof($labTypeIds))
                ->getQuery()
                ->getResult();

            if (sizeof($medicalLabIds) > 0) {
                foreach ($medicalLabIds as $item) {
                    if (!in_array($item, $medIds)) {
                        array_push($medIds, $item);
                    }
                }
            }

        }


        if ($medIds) {
            $qb
                ->andWhere($qb->expr()->in('n.id', ':medIds'))
                ->setParameter(':medIds', $medIds);
        }

        if ($medName) {
            $qb
                ->andWhere('n.name like :medName')
                ->setParameter(':medName', '%' . $medName . '%');
        }

        $qb->andWhere('n.isDone = 1');

        if ($type != 3) {
            $countQueryBuilder = clone $qb;
            $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
            $qb
                ->setFirstResult(($page - 1) * $pagesize)
                ->setMaxResults($pagesize);
        }

        /**@var Medicals[] $medical */
        $medical = $qb
            ->orderBy('n.isOntsloh', 'desc')
            ->addOrderBy('n.createdDate', 'asc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:LaboratoryType')->createQueryBuilder('n');
        /**@var LaboratoryType[] $labType */
        $labType = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:MedicalType')->createQueryBuilder('n');
        /**@var MedicalType[] $medType */
        $medType = $qb
            ->select('n.id, n.name, n.img, n.imgActive')
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();
        return $this->render('@happyWeb/Medical/medicals.html.twig',
            array(
                'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
                'count' => $count,
                'page' => $page,
                'labType' => $labType,
                'medical' => $medical,
                'viewType' => $type,
                'medType' => $medType,
                'labTypeIds' => $labTypeIds,
                'medTypeId' => $medTypeIds
            )
        );
    }

    /**
     * Remove Medical entity.
     *
     * @Route("/detail/{id}", name="medical_detail" , requirements={"id" = "\d+"})
     *
     */
    public function medicalDetailAction(Medicals $medicals)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:MedicalPhoto')->createQueryBuilder('n');
        $medicalPhoto = $qb
            ->where('n.medical = :medid')
            ->setParameter('medid', $medicals)
            ->orderBy('n.sortId', 'asc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('n');
        $medicalLabType = $qb
            ->leftJoin('n.labType', 'mt')
            ->addSelect("mt")
            ->where('n.medical = :medid')
            ->setParameter('medid', $medicals)
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('n');
        $medicalDoctor = $qb
            ->where('n.medical = :medid')
            ->setParameter('medid', $medicals)
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:LaboratoryType')->createQueryBuilder('n');

        /**@var LaboratoryType[] $labType */
        $labType = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        $tasagInfo = json_decode($medicals->getTasagInfo());


        $qb = $em->getRepository('happyCmsBundle:MedicalType')->createQueryBuilder('n');
        /**@var MedicalType[] $medType */
        $medType = $qb
            ->select('n.id, n.name, n.img, n.imgActive')
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Medical/medical_detail.html.twig',
            array(
                'medical' => $medicals,
                'medicalphoto' => $medicalPhoto,
                'medicalLabType' => $medicalLabType,
                'medicalDoctor' => $medicalDoctor,
                'tasagInfo' => $tasagInfo,
                'labType' => $labType,
                'viewType' => 1,
                'medType' => $medType,
            )
        );
    }
}
