<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
}