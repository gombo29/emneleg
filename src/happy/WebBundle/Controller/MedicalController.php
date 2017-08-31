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
     * @Route("/{page}/{type}", name="medicals", requirements={"page" = "\d+", "type" = "\d+"}, defaults={"page" = 1, "type" = 1} )
     * @Method({"GET", "POST"})
     * Төслүүд
     *
     */
    public function medicalsAction(Request $request, $page, $type)
    {
        $pagesize = 20;
        $count = 0;
        $labTypeIds = $request->get('medtypes');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');


        if ($labTypeIds) {
            $qblab = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('n');

            $medicalIds = $qblab
                ->select('m.id')
                ->leftJoin('n.medical', 'm')
                ->where($qblab->expr()->in('n.labType', ':p1'))
                ->setParameter('p1', $labTypeIds)
                ->groupBy('m.id')
                ->having('COUNT(m.id) = :labcount')
                ->setParameter('labcount', sizeof($labTypeIds))
                ->getQuery()
                ->getArrayResult();

            if ($medicalIds) {
                $qb
                    ->where($qb->expr()->in('n.id', ':medIds'))
                    ->setParameter(':medIds', $medicalIds);
            }

        }


        if ($type == 1) {
            $countQueryBuilder = clone $qb;
            $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
            $qb
                ->setFirstResult(($page - 1) * $pagesize)
                ->setMaxResults($pagesize);
        }

        /**@var Medicals[] $medical */
        $medical = $qb
            ->orderBy('n.isOntsloh', 'desc')
            ->addOrderBy('n.createdDate', 'desc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:LaboratoryType')->createQueryBuilder('n');
        /**@var LaboratoryType[] $labType */
        $labType = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        $medType = null;


        $qb = $em->getRepository('happyCmsBundle:MedicalType')->createQueryBuilder('n');
        /**@var MedicalType[] $medType */
        $medType = $qb
            ->select('n.id, n.name')
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
            )
        );
    }

    /**
     * Remove Medical entity.
     *
     * @Route("/detail/{id}", name="medical_detail" , requirements={"id" = "\d+"})
     *
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

        return $this->render('@happyWeb/Medical/medical_detail.html.twig',
            array(
                'medical' => $medicals,
                'medicalphoto' => $medicalPhoto,
                'medicalLabType' => $medicalLabType,
                'medicalDoctor' => $medicalDoctor,
                'tasagInfo' => $tasagInfo,
                'labType' => $labType,
                'viewType' => 1,
            )
        );
    }
}
