<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\MedicalLabType;
use happy\CmsBundle\Entity\MedicalMedType;
use happy\CmsBundle\Entity\MedicalPhoto;
use happy\CmsBundle\Entity\Medicals;
use happy\CmsBundle\Entity\MedicalType;
use happy\CmsBundle\Entity\UserLogs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Route("/{page}", name="cms_medical_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;

        $searchEntity = new Medicals();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\MedicalSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $isDone = $request->get('isDone');

        if ($isDone == null) {
            $isDone = 1;

        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('n.name like :name')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }

            if ($searchForm->get('ehlehDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate > :ehlehDate')
                    ->setParameter('ehlehDate', $searchEntity->getEhlehDate());
            }

            if ($searchForm->get('duusahDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate < :duusahDate')
                    ->setParameter('duusahDate', $searchEntity->getDuusahDate());
            }
        }


        $qb
            ->andWhere('n.isDone = :isDone')
            ->setParameter('isDone', $isDone);


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


        return $this->render('@happyCms/Medical/index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'search' => $search,
            'medical' => $medical,
            'isDone' => $isDone,
            'searchform' => $searchForm->createView(),
        ));
    }


    /**
     * Creates a new Medicals entity.
     *
     * @Route("/new", name="cms_medical_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $medical = new Medicals();
        $form = $this->createForm('happy\CmsBundle\Form\MedicalType', $medical);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $medicalType = $em->getRepository('happyCmsBundle:MedicalType')->findAll();
        $labType = $em->getRepository('happyCmsBundle:LaboratoryType')->findAll();
        if ($form->isSubmitted() && $form->isValid()) {

            $medtype = $request->request->get('medtype');

            $oroo1too = $request->request->get('1oroo');
            $oroo2too = $request->request->get('2oroo');
            $oroo3too = $request->request->get('3oroo');
            $oroo4too = $request->request->get('4oroo');
            $oroo5too = $request->request->get('5oroo');
            $oroo6too = $request->request->get('6oroo');
            $oroo7too = $request->request->get('7oroo');

            $une1 = $request->request->get('1une');
            $une2 = $request->request->get('2une');
            $une3 = $request->request->get('3une');
            $une4 = $request->request->get('4une');
            $une5 = $request->request->get('5une');
            $une6 = $request->request->get('6une');
            $une7 = $request->request->get('7une');

            $oroo1Ontslog = $request->request->get('1orooOntslog');
            $oroo2Ontslog = $request->request->get('2orooOntslog');
            $oroo3Ontslog = $request->request->get('3orooOntslog');
            $oroo4Ontslog = $request->request->get('4orooOntslog');
            $oroo5Ontslog = $request->request->get('5orooOntslog');
            $oroo6Ontslog = $request->request->get('6orooOntslog');
            $oroo7Ontslog = $request->request->get('7orooOntslog');


            $arrTasag = array(
                [

                    'oroo_ner' => '1 хүний өрөө',
                    'oroo_too' => $oroo1too,
                    'oroo_une' => $une1,
                    'oroo_ontslog' => $oroo1Ontslog
                ],
                [
                    'oroo_ner' => '2 хүний өрөө',
                    'oroo_too' => $oroo2too,
                    'oroo_une' => $une2,
                    'oroo_ontslog' => $oroo2Ontslog
                ],

                [
                    'oroo_ner' => '3 хүний өрөө',
                    'oroo_too' => $oroo3too,
                    'oroo_une' => $une3,
                    'oroo_ontslog' => $oroo3Ontslog
                ],
                [
                    'oroo_ner' => '4 хүний өрөө',
                    'oroo_too' => $oroo4too,
                    'oroo_une' => $une4,
                    'oroo_ontslog' => $oroo4Ontslog
                ],

                [
                    'oroo_ner' => '5 хүний өрөө',
                    'oroo_too' => $oroo5too,
                    'oroo_une' => $une5,
                    'oroo_ontslog' => $oroo5Ontslog
                ],
                [
                    'oroo_ner' => '6 хүний өрөө',
                    'oroo_too' => $oroo6too,
                    'oroo_une' => $une6,
                    'oroo_ontslog' => $oroo6Ontslog
                ],

                [
                    'oroo_ner' => '6-с дээш хүний өрөө',
                    'oroo_too' => $oroo7too,
                    'oroo_une' => $une7,
                    'oroo_ontslog' => $oroo7Ontslog
                ],
            );
            $labtype = $request->request->get('labtype');
            $json = json_encode($arrTasag);
            $medical->setTasagInfo($json);

            if ($labtype != null) {
                foreach ($labtype as $l) {
                    $labtypePrice = $request->request->get('labtype' . $l);
                    $medicalLabType = new MedicalLabType();
                    $medicalLabType->setMedical($medical);
                    $medicalLabType->setLabType($em->getReference('happyCmsBundle:LaboratoryType', $l));
                    $medicalLabType->setPrice($labtypePrice);
                    $em->persist($medicalLabType);
                }
            }

            if ($medtype != null) {
                foreach ($medtype as $m) {
                    $medicalMedType = new MedicalMedType();
                    $medicalMedType->setMedical($medical);
                    $medicalMedType->setMedicalType($em->getReference('happyCmsBundle:MedicalType', $m));
                    $em->persist($medicalMedType);
                }
            }


            $medical->uploadImage($this->container);
            $em->persist($medical);
            $em->flush();

            $log = new UserLogs();
            $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
            $log->setIpaddress($request->getClientIp());
            $log->setValue($medical->getName());
            $log->setAction('Эмнэлэг үүсгэв.');
            $log->setMedId($medical->getId());
            $log->setCreatedDate(new \DateTime('now'));
            $em->persist($log);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Эмнэлэг амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_medical_index');
        }
        return array(
            'form' => $form->createView(),
            'medtype' => $medicalType,
            'labtype' => $labType,
        );
    }


    /**
     * Show medical entity.
     *
     * @Route("/show/{id}", name="cms_medical_show" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Medicals $medical)
    {
        $tasagInfo = json_decode($medical->getTasagInfo());
        $em = $this->getDoctrine()->getManager();
        $qbmed = $em->getRepository('happyCmsBundle:MedicalMedType')->createQueryBuilder('m');
        $qblab = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('l');
        $qbphoto = $em->getRepository('happyCmsBundle:MedicalPhoto')->createQueryBuilder('p');
        $qbdoctor = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('d');


        $medicalMedType = $qbmed
            ->select('m.id, mt.name')
            ->leftJoin('m.medicalType', 'mt')
            ->where('m.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->getQuery()
            ->getArrayResult();

        $medicalLabType = $qblab
            ->select('l.id, l.price, lt.name')
            ->leftJoin('l.labType', 'lt')
            ->where('l.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->getQuery()
            ->getArrayResult();

        $medicalPhoto = $qbphoto
            ->where('p.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->getQuery()
            ->getArrayResult();

        $medicalDoctor = $qbdoctor
            ->where('d.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->getQuery()
            ->getArrayResult();


        return array(
            'medical' => $medical,
            'tasagInfo' => $tasagInfo,
            'medicalMedType' => $medicalMedType,
            'medicalLabType' => $medicalLabType,
            'medicalPhoto' => $medicalPhoto,
            'medicalDoctor' => $medicalDoctor,
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_medical_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Medicals $medical)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\MedicalType', $medical);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $allMedicalType = $em->getRepository('happyCmsBundle:MedicalType')->createQueryBuilder('m')->getQuery()->getArrayResult();
        $allLabType = $em->getRepository('happyCmsBundle:LaboratoryType')->createQueryBuilder('l')->getQuery()->getArrayResult();

        $tasagInfo = json_decode($medical->getTasagInfo());

        $qbmed = $em->getRepository('happyCmsBundle:MedicalMedType')->createQueryBuilder('m');
        $qblab = $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('l');

        $selectedMedicalType = $qbmed
            ->select('mt.id')
            ->leftJoin('m.medicalType', 'mt')
            ->where('m.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->getQuery()
            ->getArrayResult();

        $selectedLabType = $qblab
            ->select('lt.id, l.price')
            ->leftJoin('l.labType', 'lt')
            ->where('l.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->getQuery()
            ->getArrayResult();

        foreach ($allMedicalType as $index => $l) {
            $isSelected = null;
            foreach ($selectedMedicalType as $sl) {

                if ($sl['id'] == $l['id']) {
                    $isSelected = $sl;
                }
            }
            if ($isSelected != null) {
                $allMedicalType[$index]['selected'] = true;
            }
        }
        foreach ($allLabType as $index => $l) {
            $isSelected = null;
            foreach ($selectedLabType as $sl) {
                if ($sl['id'] == $l['id']) {
                    $isSelected = $sl;
                    $price = $sl['price'];
                }
            }
            if ($isSelected != null) {
                $allLabType[$index]['selected'] = true;

                if ($price) {
                    $allLabType[$index]['price'] = $price;
                }
            }
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $medtype = $request->request->get('medtype');
            $labtype = $request->request->get('labtype');


            $em->getRepository('happyCmsBundle:MedicalLabType')->createQueryBuilder('s')
                ->delete()
                ->where('s.medical = :med')
                ->setParameter('med', $medical->getId())
                ->getQuery()->execute();

            $em->getRepository('happyCmsBundle:MedicalMedType')->createQueryBuilder('s')
                ->delete()
                ->where('s.medical = :med')
                ->setParameter('med', $medical->getId())
                ->getQuery()->execute();

            if ($labtype != null) {
                foreach ($labtype as $l) {
                    $labtypePrice = $request->request->get('labtype' . $l);
                    $medicalLabType = new MedicalLabType();
                    $medicalLabType->setMedical($medical);
                    $medicalLabType->setLabType($em->getReference('happyCmsBundle:LaboratoryType', $l));
                    $medicalLabType->setPrice($labtypePrice);
                    $em->persist($medicalLabType);
                }
            }

            if ($medtype != null) {
                foreach ($medtype as $m) {
                    $medicalMedType = new MedicalMedType();
                    $medicalMedType->setMedical($medical);
                    $medicalMedType->setMedicalType($em->getReference('happyCmsBundle:MedicalType', $m));
                    $em->persist($medicalMedType);
                }
            }

            $oroo1too = $request->request->get('1oroo');
            $oroo2too = $request->request->get('2oroo');
            $oroo3too = $request->request->get('3oroo');
            $oroo4too = $request->request->get('4oroo');
            $oroo5too = $request->request->get('5oroo');
            $oroo6too = $request->request->get('6oroo');
            $oroo7too = $request->request->get('7oroo');

            $une1 = $request->request->get('1une');
            $une2 = $request->request->get('2une');
            $une3 = $request->request->get('3une');
            $une4 = $request->request->get('4une');
            $une5 = $request->request->get('5une');
            $une6 = $request->request->get('6une');
            $une7 = $request->request->get('7une');

            $oroo1Ontslog = $request->request->get('1orooOntslog');
            $oroo2Ontslog = $request->request->get('2orooOntslog');
            $oroo3Ontslog = $request->request->get('3orooOntslog');
            $oroo4Ontslog = $request->request->get('4orooOntslog');
            $oroo5Ontslog = $request->request->get('5orooOntslog');
            $oroo6Ontslog = $request->request->get('6orooOntslog');
            $oroo7Ontslog = $request->request->get('7orooOntslog');


            $arrTasag = array(
                [

                    'oroo_ner' => '1 хүний өрөө',
                    'oroo_too' => $oroo1too,
                    'oroo_une' => $une1,
                    'oroo_ontslog' => $oroo1Ontslog
                ],
                [
                    'oroo_ner' => '2 хүний өрөө',
                    'oroo_too' => $oroo2too,
                    'oroo_une' => $une2,
                    'oroo_ontslog' => $oroo2Ontslog
                ],

                [
                    'oroo_ner' => '3 хүний өрөө',
                    'oroo_too' => $oroo3too,
                    'oroo_une' => $une3,
                    'oroo_ontslog' => $oroo3Ontslog
                ],
                [
                    'oroo_ner' => '4 хүний өрөө',
                    'oroo_too' => $oroo4too,
                    'oroo_une' => $une4,
                    'oroo_ontslog' => $oroo4Ontslog
                ],

                [
                    'oroo_ner' => '5 хүний өрөө',
                    'oroo_too' => $oroo5too,
                    'oroo_une' => $une5,
                    'oroo_ontslog' => $oroo5Ontslog
                ],
                [
                    'oroo_ner' => '6 хүний өрөө',
                    'oroo_too' => $oroo6too,
                    'oroo_une' => $une6,
                    'oroo_ontslog' => $oroo6Ontslog
                ],

                [
                    'oroo_ner' => '6-с дээш хүний өрөө',
                    'oroo_too' => $oroo7too,
                    'oroo_une' => $une7,
                    'oroo_ontslog' => $oroo7Ontslog
                ],
            );

            $json = json_encode($arrTasag);
            $medical->setTasagInfo($json);
            $medical->uploadImage($this->container);


            $log = new UserLogs();
            $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
            $log->setIpaddress($request->getClientIp());
            $log->setMedId($medical->getId());
            $log->setValue($medical->getName());
            $log->setAction('Эмнэлэг засав.');
            $log->setCreatedDate(new \DateTime('now'));
            $em->persist($log);

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Эмнэлэг амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_medical_update', array('id' => $medical->getId()));
        }

        return array(
            'medalltype' => $allMedicalType,
            'laballtype' => $allLabType,
            'medical' => $medical,
            'tasagInfo' => $tasagInfo,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Config medical entity.
     *
     * @Route("/config/{id}", name="cms_medical_config" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function configAction(Request $request, Medicals $medical)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\MedicalConfigType', $medical);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $log = new UserLogs();
            $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
            $log->setIpaddress($request->getClientIp());
            $log->setValue($medical->getName());
            $log->setAction('Эмнэлэг тохиргоо өөрчлөв.');
            $log->setMedId($medical->getId());
            $log->setCreatedDate(new \DateTime('now'));
            $em->persist($log);


            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Эмнэлэг амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_medical_index');
        }

        return array(
            'medical' => $medical,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Add image medical entity.
     *
     * @Route("/image/{id}", name="cms_medical_image" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function imageAction(Request $request, Medicals $medical)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:MedicalPhoto')->createQueryBuilder('p');
        $photos = $qb
            ->where('p.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->orderBy('p.sortId', 'asc')
            ->getQuery()
            ->getArrayResult();

        return array(
            'medical' => $medical,
            'photos' => $photos,
        );
    }

    /**
     * Add image medical entity.
     *
     * @Route("/imagenew/{id}", name="cms_medical_image_new" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function imagenewAction(Request $request, Medicals $medical)
    {
        return array(
            'medical' => $medical,
        );
    }

    /**
     * Add image medical entity.
     *
     * @Route("/uploadimage/{id}", name="cms_medical_upload_image" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function uploadimgAction(Request $request, Medicals $medical)
    {
        $em = $this->getDoctrine()->getManager();
        $images = $request->files->get('files');
        $photo = new MedicalPhoto();
        $photo->setMedical($medical);
        $photo->imagefile = $images[0];

        $photo->uploadImage($this->container, false);

        $em->persist($photo);

        $log = new UserLogs();
        $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
        $log->setIpaddress($request->getClientIp());
        $log->setValue($medical->getName());
        $log->setAction('Эмнэлэг зураг үүсгэв.');
        $log->setMedId($medical->getId());
        $log->setCreatedDate(new \DateTime('now'));
        $em->persist($log);

        $em->flush();

        $entities = array(
            'files' => array(
                array(
                    'id' => $photo->getId(),
                    'name' => $images[0]->getClientOriginalName(),
                    'type' => $images[0]->getClientOriginalExtension(),
                    'deleteType' => 'DELETE',
                    'url' => 'https://gogo.mn',
                    'deleteUrl' => $this->generateUrl('cms_medical_delete_image', array('id' => $photo->getId())),
                    'thumbnailUrl' => '/' . $this->container->getParameter('localstatfolder') . $photo->getPath(),
                    'descr' => 'Энд зургийн тайлбар оруулна уу!'
                ))
        );

        return new JsonResponse($entities);
    }


    /**
     * Add image medical entity.
     *
     * @Route("/deleteimage/{id}", name="cms_medical_delete_image" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function deleteimgAction(Request $request, MedicalPhoto $medicalPhoto)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($medicalPhoto);

        $log = new UserLogs();
        $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
        $log->setIpaddress($request->getClientIp());
        $log->setValue($medicalPhoto->getMedical()->getName());
        $log->setAction('Эмнэлэг зураг утсгав.');
        $log->setMedId($medicalPhoto->getMedical()->getId());
        $log->setCreatedDate(new \DateTime('now'));
        $em->persist($log);

        $em->flush();

        return $this->redirectToRoute('cms_medical_image', array('id' => $medicalPhoto->getMedical()->getId()));
    }


    /**
     * Add image medical entity.
     *
     * @Route("/descimage", name="cms_medical_desc_image")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateDescImageAction(Request $request)
    {

        $imageid = $request->get('imageid');
        $text = $request->get('text');

        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('happyCmsBundle:MedicalPhoto')->find($imageid);
        $image->setTailbar($text);
        $em->persist($image);

        $log = new UserLogs();
        $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
        $log->setIpaddress($request->getClientIp());
        $log->setValue($image->getMedical()->getName());
        $log->setAction('Эмнэлэг зурагт тайлбар үүсгэв.');
        $log->setMedId($image->getMedical()->getId());
        $log->setCreatedDate(new \DateTime('now'));
        $em->persist($log);

        $em->flush();

        return new JsonResponse(array(
            'status' => 'ok',
        ));
    }

    /**
     *
     * Add image medical entity.
     *
     * @Route("/changeorder", name="cms_medical_change_order")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function imgChangeOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ids = $request->request->get('ids');

        foreach ($ids as $index => $alauid) {
            $qb = $em->getRepository('happyCmsBundle:MedicalPhoto')->createQueryBuilder('p');
            $entity = $qb
                ->where('p.id = :id')
                ->setParameter('id', $alauid)
                ->getQuery()
                ->getSingleResult();

            if (!$entity) continue;

            $entity->setSortId($index);
            $em->persist($entity);
        }
        $em->flush();

        return new JsonResponse(array(
            'status' => 'success',
        ));
    }

    /**
     * Add image medical entity.
     *
     * @Route("/doctor/{id}", name="cms_medical_doctor" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function doctorAction(Medicals $medical)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Doctors')->createQueryBuilder('p');

        /**@var Doctors[] $doctors */
        $doctors = $qb
            ->where('p.medical = :medid')
            ->setParameter('medid', $medical->getId())
            ->getQuery()
            ->getArrayResult();

        return array(
            'doctors' => $doctors,
            'medical' => $medical,
        );
    }

    /**
     * Creates a new doctors entity.
     *
     * @Route("/doctor/new", name="cms_medical_doctor_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function doctornewAction(Request $request)
    {
        $id = $request->get('id');
        $doctor = new Doctors();
        $form = $this->createForm('happy\CmsBundle\Form\DoctorType', $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $doctor->uploadImage($this->container);
            $doctor->setIsDoctor(true);
            $doctor->setMedical($em->getReference('happyCmsBundle:Medicals', $id));
            $em->persist($doctor);

            $log = new UserLogs();
            $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
            $log->setIpaddress($request->getClientIp());
            $log->setValue($doctor->getMedical()->getName());
            $log->setAction('Эмнэлэг эмч үүсгэв.');
            $log->setMedId($doctor->getMedical()->getId());
            $log->setCreatedDate(new \DateTime('now'));
            $em->persist($log);

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Эмч амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_medical_doctor', array('id' => $id));
        }

        return array(
            'medical' => $id,
            'form' => $form->createView(),
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/doctor/update/{id}", name="cms_medical_doctor_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function doctorupdateAction(Request $request, Doctors $doctor)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\DoctorType', $doctor);
        $editForm->handleRequest($request);
        $id = $request->get('medid');
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $doctor->uploadImage($this->container);

            $log = new UserLogs();
            $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
            $log->setIpaddress($request->getClientIp());
            $log->setValue($doctor->getMedical()->getName());
            $log->setAction('Эмнэлэг эмч засав.');
            $log->setMedId($doctor->getMedical()->getId());
            $log->setCreatedDate(new \DateTime('now'));
            $em->persist($log);

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Эмч амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_medical_doctor_update', array('id' => $doctor->getId(), 'medid' => $id));
        }

        return array(
            'img' => $doctor->getPhoto(),
            'medicalid' => $id,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Updates banner entity.
     *
     * @Route("/doctor/delete/{id}", name="cms_medical_doctor_delete" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function doctordeleteAction(Request $request, Doctors $doctor)
    {
        $id = $request->get('medid');
        $em = $this->getDoctrine()->getManager();
        $em->remove($doctor);

        $log = new UserLogs();
        $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
        $log->setIpaddress($request->getClientIp());
        $log->setValue($doctor->getMedical()->getName());
        $log->setAction('Эмнэлэг эмч устгав.');
        $log->setMedId($doctor->getMedical()->getId());
        $log->setCreatedDate(new \DateTime('now'));
        $em->persist($log);

        $em->flush();
        return $this->redirectToRoute('cms_medical_doctor', array('id' => $id));
    }


    /* ==================== Medical Type ===================== */

    /**
     *  Lists all content entities.
     *
     * @Route("/medical-type/{page}", name="cms_medical_type_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function medicalTypeAction(Request $request, $page)
    {
        $pagesize = 20;


        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:MedicalType')->createQueryBuilder('n');


        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();

        /**@var MedicalType[] $medType */
        $medType = $qb
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyCmsBundle:Medical:medical-type.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'medType' => $medType,
        ));
    }


    /**
     * Creates a new medical type entity.
     *
     * @Route("/medical-type/new", name="cms_medical_type_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function medicaltypenewAction(Request $request)
    {
        $medType = new MedicalType();
        $form = $this->createForm('happy\CmsBundle\Form\MedicalMedType', $medType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $medType->uploadImage($this->container);
            $medType->uploadImageActive($this->container);
            $medType->uploadImageMobile($this->container);
            $em->persist($medType);

            $log = new UserLogs();
            $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
            $log->setIpaddress($request->getClientIp());
            $log->setValue($medType->getName());
            $log->setAction('Эмнэлэг төрөл нэмэв.');
            $log->setMedId($medType->getId());
            $log->setCreatedDate(new \DateTime('now'));
            $em->persist($log);

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Эмнэлгийн төрөл амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_medical_type_index');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Updates medical type entity.
     *
     * @Route("/medical-type/update/{id}", name="cms_medical_type_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function medicaltypeupdateAction(Request $request, MedicalType $medType)
    {
        $editForm = $this->createForm('happy\CmsBundle\Form\MedicalMedType', $medType);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $medType->uploadImage($this->container);
            $medType->uploadImageActive($this->container);
            $medType->uploadImageMobile($this->container);

            $log = new UserLogs();
            $log->setAdminname($this->container->get('security.context')->getToken()->getUser()->getUsername());
            $log->setIpaddress($request->getClientIp());
            $log->setValue($medType->getName());
            $log->setAction('Эмнэлэг төрөл засав.');
            $log->setMedId($medType->getId());
            $log->setCreatedDate(new \DateTime('now'));
            $em->persist($log);

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Эмнэлгийн төрөл амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_medical_type_update', array('id' => $medType->getId()));
        }

        return array(
            'img' => $medType->getImg(),
            'imgActive' => $medType->getImgActive(),
            'imgMobile' => $medType->getImgMobile(),
            'edit_form' => $editForm->createView(),
        );
    }

}