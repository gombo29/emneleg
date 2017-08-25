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

        $searchEntity = new Medicals();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\MedicalSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('n.name like :name')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }
        }

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        /**@var Medicals[] $medical */
        $medical = $qb
            ->orderBy('n.createdDate', 'desc')
            ->addOrderBy('n.isOntsloh', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
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
                'search' => $search,
                'labType' => $labType,
                'medical' => $medical,
                'viewType' => $type,
                'medType' => $medType,
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
            )
        );
    }
}
