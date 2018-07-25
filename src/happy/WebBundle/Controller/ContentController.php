<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\Content;
use happy\WebBundle\Form\AdviceSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Content controller.
 *
 * @Route("/advice")
 */
class ContentController extends Controller
{
    /**
     * @Route("/advice/{page}", name="advice", requirements={"page" = "\d+"}, defaults={"page" = 1} )
     * @Method({"GET", "POST"})
     * Зөвлөгөөнүүд
     *
     */
    public function adviceAction(Request $request, $page)
    {

        $pagesize = 11;
        $searchEntity = new Content();
        $searchform = $this->createForm(new AdviceSearchType(), $searchEntity);
        $search = false;
        $em = $this->getDoctrine()->getManager();

        if ($request->get("submit") == 'submit') {
            $searchform->bind($request);
            $search = true;
        }
        /**@var Content[] $content */
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('LOWER(n.name) like LOWER(:name)')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }
        }
        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();

        $advice = $qb
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->orderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('@happyWeb/Content/advice.html.twig',
            array(
                'id' => 3,
                'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
                'advice' => $advice,
                'search' => $search,
                'pagesize' => $pagesize,
                'page' => $page,
                'count' => $count,
                'searchform' => $searchform->createView(),
            )
        );
    }

    protected function fblikecount($url)
    {
        $count = 0;
        $is_share = false;
        $is_comment_count = false;
        $is_share_count = false;
        $json = json_decode(file_get_contents('http://graph.facebook.com/' . $url));
        foreach ($json as $key => $val) {
            if ($key == "share") {
                $is_share = true;
                foreach ($val as $k => $v) {
                    if ($k == "comment_count") {
                        $is_comment_count = true;
                    } elseif ($k == "share_count") {
                        $is_share_count = true;
                    }
                }
            }
        }
        if ($is_share && $is_comment_count && $is_share_count) {
            $count = $json->share->share_count;
        }
        return $count;
    }


    /**
     * Remove Advice entity.
     *
     * @Route("/detail/{id}", name="advice_detail" , requirements={"id" = "\d+"})
     *
     *
     */
    public function adviceDetailAction(Content $content)
    {
        $em = $this->getDoctrine()->getManager();
        /**@var Content[] $content */
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');
        $advices = $qb
            ->setMaxResults(20)
            ->orderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();

        $count  =$this->fblikecount('http://dev.tester.em/advice/detail/' . $content->getId());

        $content->setLikeCount($count);
        $em->flush();

        return $this->render('@happyWeb/Content/advice_detail.html.twig',
            array(
                'id' => 3,
                'advice' => $content,
                'advices' => $advices,
            )
        );
    }
}
