<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\DoctorLog;
use happy\CmsBundle\Entity\DoctorPosition;
use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\DoctorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Nurse controller.
 *
 * @Route("/nurse")
 */
class NurseController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{page}", name="web_nurse_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $positionId = $request->get('position');
        $serviceId = $request->get('services');


        if ($positionId == null && $serviceId == null) {
            $positionId = 1;
            $serviceId = 1;
        }

        $qb = $em->getRepository('happyCmsBundle:DoctorQpay')->createQueryBuilder('n');
        /**@var Doctors[] $nurse */
        $nurse = $qb
            ->leftJoin('n.doctor', 'd')
            ->addSelect('d')
            ->leftJoin('n.doctorType', 'dt')
            ->addSelect('dt')
            ->leftJoin('n.doctorPosition', 'dp')
            ->addSelect('dp')
            ->andWhere('dp.id = :posid')
            ->setParameter('posid', $positionId)
            ->andWhere('dt.id = :serid')
            ->setParameter('serid', $serviceId)
            ->andWhere('d.isDoctor = 0')
            ->andWhere('d.isShow = 1')
            ->orderBy('d.createdDate', 'asc')
            ->addOrderBy('d.star', 'desc')
            ->groupBy('d.id')
            ->getQuery()
            ->getArrayResult();


        $qb = $em->getRepository('happyCmsBundle:DoctorType')->createQueryBuilder('n');
        /**@var DoctorType[] $nurseType */
        $nurseType = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:DoctorPosition')->createQueryBuilder('n');
        /**@var DoctorPosition[] $nursePosition */
        $nursePosition = $qb
            ->orderBy('n.id', 'asc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Nurse/index.html.twig', array(
            'nurse' => $nurse,
            'nurseService' => $nurseType,
            'nursePosition' => $nursePosition,
            'positionId' => $positionId,
            'serviceId' => $serviceId,
        ));
    }

    /**
     * Updates doctor entity.
     *
     * @Route("/register", name="cms_nurse_register" )
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $nurse = new Doctors();
        $form = $this->createForm('happy\WebBundle\Form\NurseType', $nurse);
        $form->handleRequest($request);
        $s = $request->get('s');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $nurse->uploadImage($this->container);
            $nurse->setIsDoctor(0);
            $em->persist($nurse);
            $em->flush();
            return $this->redirectToRoute('cms_nurse_register', array('s' => 1));
        }

        return array(
            'nurse' => $nurse,
            'form' => $form->createView(),
            's' => $s
        );
    }


    /**
     * Updates doctor entity.
     *
     * @Route("/call-register", name="cms_nurse_call_register" )
     * @Method({"GET", "POST"})
     */
    public function addDoctorLogAction(Request $request)
    {
        $nurseid = $request->get('nurseid');
        $nursename = $request->get('nursename');
        $nurseprice = $request->get('price');
        $service = $request->get('service');
        $position = $request->get('position');
        $em = $this->container->get('doctrine')->getManager();
        $doctorlog = new DoctorLog();
        $doctorlog->setType($service);
        $doctorlog->setDoctorId($nurseid);
        $doctorlog->setDoctorName($nursename);
        $doctorlog->setPrice($nurseprice);
        $doctorlog->setPosition($position);
        $em->persist($doctorlog);
        $em->flush();
        return new JsonResponse(array(
            'status' => 'ok',
        ));

    }
}
