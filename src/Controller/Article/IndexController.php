<?php

namespace Bike\Dashboard\Controller\Article;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;

/**
 * @Route("/article")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     * @Template("BikeDashboardBundle:article/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $articleService = $this->get('bike.dashboard.service.article');
        $page = $request->query->get('p');
        $pageNum = 10;
        $args = $request->query->all();
        return $articleService->searchArticle($args, $page, $pageNum);
    }

    /**
     * @Route("/new", name="article_new")
     * @Template("BikeDashboardBundle:article/index:new.html.twig")
     */
    public function newAction(Request $request)
    {
    	$categoryDao = $this->container->get('bike.dashboard.dao.primary.article');
        $categoryList = $categoryDao->findList('*', array(), 0, 0);
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            $articleService = $this->get('bike.dashboard.service.article');
            try {
                $articleService->createArticle($data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        }
        return ['categoryList'=>$categoryList];
    }

    /**
     * @Route("/edit/{id}", name="article_edit")
     * @Template("BikeDashboardBundle:article/index:edit.html.twig")
     */
    public function editAction(Request $request,$id)
    {
        $articleService = $this->get('bike.dashboard.service.article');
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            try {
                $articleService->editArticle($id,$data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        } else {
            $article = $articleService->getArticle($id);
            return ['article'=>$article];
        }
        return array();
    }

}
