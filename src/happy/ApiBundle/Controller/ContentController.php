<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\Content;
use happy\CmsBundle\Entity\MedicalPhoto;
use happy\CmsBundle\Entity\Medicals;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * content controller.
 *
 * @Route("/content")
 */
class ContentController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/list/{page}", name="api_content_list", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 10;
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        $content = $qb
            ->select('n.id', 'n.name', 'n.headline', 'n.img', 'n.likeCount')
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();


        return new JsonResponse(
            array(
                "content" => $content,
                "pagesize" => $pagesize,
                'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
                'count' => $count,
                'page' => $page)
        );
    }

    /**
     * Remove Medical entity.
     *
     * @Route("/detail/{id}", name="api_content_detail" , requirements={"id" = "\d+"})
     *
     *
     */
    public function contentDetailAction(Content $content)
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');
        $contentGeneral = $qb
            ->where('n.id = :contid')
            ->setParameter('contid', $content)
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Content/content-api.html.twig',
            array(
                'info' => $contentGeneral,
            )
        );


    }

    public function getLatinLetters()
    {
        return array(
            'a', 'b', 'v', 'g', 'd', 'e', 'yo',
            'j', 'z', 'i', 'i', 'k', 'l', 'm',
            'n', 'o', 'o', 'p', 'r', 's', 't',
            'u', 'u', 'f', 'h', 'ts', 'ch', 'sh',
            'sh', '', 'ii', '', 'e', 'yu', 'ya');
    }

    public function getCyrillicLetters()
    {
        return array(
            'а', 'б', 'в', 'г', 'д', 'е', 'ё',
            'ж', 'з', 'и', 'й', 'к', 'л', 'м',
            'н', 'о', 'ө', 'п', 'р', 'с', 'т',
            'у', 'ү', 'ф', 'х', 'ц', 'ч', 'ш',
            'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
        );
    }


    /**
     * Remove Medical entity.
     *
     * @Route("/parser", name="parser" )
     *
     *
     */
    public function parserAction()
    {

        $crawler = new Crawler();
        $crawler->addXmlContent(
            file_get_contents(
                'data.xml'
            )
        );

        $xml = simplexml_load_file('data.xml');
        $arrNames = array();
        $arrDesc = array();

        $i = 0;
        $thi = 0;
        $tho = 0;
        foreach ($xml as $key => $data) {
            $i = $i + 1;
            if ($i <= 30) {
                $arr1 = array();
                if ($i < 10) {
                    $nameid = 'a0' . $i . 'list';
                } else {
                    $nameid = 'a' . $i . 'list';
                }
                if ((string)$data['name'] === $nameid) {
                    $thi = $thi + sizeof($data->item);
                    foreach ($data->item as $m) {
                        array_push($arrNames, (string)$m);
                    }
                }
            } else
                if ($i > 31) {
                    $val = $i - 31;

                    if ($val < 10) {
                        $descid = 'data0' . $val;
                    } else {
                        $descid = 'data' . $val;
                    }

                    if ((string)$data['name'] === $descid) {
                        $tho = $tho + sizeof($data->item);
                        foreach ($data->item as $k) {
                            array_push($arrDesc, (string)$k);
                        }
                    }
                }
        }

        $array = array();
        for ($nm = 0; $nm <= sizeof($arrNames); $nm++) {
            if ($nm < 1238) {
                $array[$nm]['id'] = $nm;
                $array[$nm]['name'] = $arrNames[$nm];
                $array[$nm]['nameEN'] = str_replace($this->getCyrillicLetters(), $this->getLatinLetters(), mb_strtolower($arrNames[$nm]));
                $array[$nm]['desc'] = $arrDesc[$nm];
            }
        }
        return new JsonResponse($array);
    }

    /**
     * Remove Medical entity.
     *
     * @Route("/medicaljson", name="json" )
     *
     *
     */
    public function medjsonAction()
    {

        // ==================== GET MEDICAL LIST ================

//        $json = file_get_contents('https://map.minu.mn/beta/api/mobile/categories?x=0.0&y=0.0&search=300&start=0&end=10000');
//        $data = json_decode($json);
//        $mdec = new MCrypt();
//
//        $medicalData = json_decode($mdec->decrypt($data->data), true);
//        $array = $medicalData[0][0]['documents'];
//
//        $batchSize = 100;
//        $em = $this->container->get('doctrine')->getManager();

//        foreach ($array as $key => $row) {
//            if (mb_strpos(mb_strtolower($row['category']), mb_strtolower('эмнэлэг')) !== false) {
//
//                $cat = $row['category'];
//                $catNew = $pieces = explode(";", $cat);
//                $medicals = new Medicals();
//                $medicals->setName($row['companyDisplayName']);
//                $medicals->setHeadline($catNew[1]);
//                $medicals->setOldid($row['companyId']);
//                if (isset($row['galigShortName']) == true) {
//                    $medicals->setNameGalig($row['galigShortName']);
//                }
//                $medicals->setAddress($row['addressDescription']);
//                $medicals->setTimeTable($row['days']);
//                $medicals->setLongLat($row['y'] . ';' . $row['x']);
//                $medicals->setIsWifi($row['isFreeWifi']);
//                $medicals->setIsNew(true);
//                $em->persist($medicals);
//                if (($key % $batchSize) === 0) {
//                    $em->flush();
//                    $em->clear();
//                }
//            }
//        }
//
//        $em->flush();
//        $em->clear();


        // ==================== GET MEDICAL DETAIL ================


//        $em = $this->getDoctrine()->getManager();
//        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');
//        $medical = $qb
//            ->where('n.isNew = 1')
//            ->getQuery()
//            ->getArrayResult();
//        $batchSize = 100;
//
//        foreach ($medical as $key => $item) {
//            $json = file_get_contents('https://map.minu.mn/beta/api/mobile/search/company/details?companyId=' . $item['oldid'] . '&query=%D0%AD%D0%BD%D1%85%D1%81%D0%B0%D1%80%D0%B0%D0%BD%20%D1%81%D1%83%D0%B2%D0%B8%D0%BB%D0%B0%D0%BB');
//            $data = json_decode($json);
//            $mdec = new MCrypt();
//            $medicalData = json_decode($mdec->decrypt($data->data), true);
//            if ($medicalData['companyProfile']['fileId'] != '300n') {
//                $med = $em->getRepository('happyCmsBundle:Medicals')->find($item['id']);
//                $med->setPhoto($medicalData['companyProfile']['fileId']);
//                $em->persist($med);
//                if (($key % $batchSize) === 0) {
//                    $em->flush();
//                    $em->clear();
//                }
//            }
//        }
//        $em->flush();
//        $em->clear();


        // ==================== GET PHOTO FROM MINU SERVER ================

//        $batchSize = 100;
//        $em = $this->getDoctrine()->getManager();
//        $qb = $em->getRepository('happyCmsBundle:Medicals')->createQueryBuilder('n');
//        $medical = $qb
//            ->where('n.isNew = 1')
//            ->getQuery()
//            ->getArrayResult();
//
//        foreach ($medical as $key => $item) {
//            if ($item['photo'] != null) {
//                $cat = $item['photo'];
//                $catNew = $pieces = explode(";", $cat);
//
//                $dir = 'medical/old/';
//                $name = 'img' . $catNew[0] . '.jpg';
//                $med = $em->getRepository('happyCmsBundle:Medicals')->find($item['id']);
//                $med->setPhoto($dir . $name);
//                $em->persist($med);


//                foreach ($catNew as $catN) {
//                    $url = 'https://cdn.minu.mn/img/' . $catN;
//                    $resources = $this->container->getParameter('localstatfolder');
//                    $dir = 'medical/old/';
//                    $name = 'img' . $catN . '.jpg';
//                    file_put_contents($resources . '/' . $dir . $name, file_get_contents($url));
//                    $mp = new MedicalPhoto();
//                    $mp->setPath($dir . $name);
//                    $mp->setMedical($em->getReference('happyCmsBundle:Medicals', $item['id']));
//                    $em->persist($mp);
//
//                }
//            }
//        }
//        $em->flush();
//        $em->clear();

        exit();
    }
}

class MCrypt
{
    private $iv = 'fedcba9876543210';
    private $key = '0123456789abcdef';


    function __construct()
    {
    }

    function encrypt($str)
    {
        $iv = $this->iv;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $this->key, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }

    function decrypt($code)
    {
        $code = $this->hex2bin($code);
        $iv = $this->iv;

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $this->key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));
    }

    protected function hex2bin($hexdata)
    {
        $bindata = '';

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }

}

