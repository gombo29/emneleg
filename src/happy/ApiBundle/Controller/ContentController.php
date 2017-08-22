<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\Content;
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
                $array[$nm]['id'] =$nm;
                $array[$nm]['name'] = $arrNames[$nm];
                $array[$nm]['nameEN'] = str_replace($this->getCyrillicLetters(), $this->getLatinLetters(), mb_strtolower($arrNames[$nm]));
                $array[$nm]['desc'] = $arrDesc[$nm];
            }
        }
        return new JsonResponse($array);
    }
}