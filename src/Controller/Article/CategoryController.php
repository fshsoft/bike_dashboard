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
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_list")
     * @Template("BikeDashboardBundle:article/category:index.html.twig")
     */
    public function indexAction(Request $request)
    {
    	$categoryService = $this->get('bike.dashboard.service.article_category');
        $page = $request->query->get('p');
        $pageNum = 10;
        $args = $request->query->all();
        return $categoryService->searchArticleCategory($args, $page, $pageNum);
    }


    /**
     * @Route("/new", name="category_new")
     * @Template("BikeDashboardBundle:article/category:new.html.twig")
     */
    public function newAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            $categoryService = $this->get('bike.dashboard.service.article_category');
            try {
                $categoryService->createArticleCategory($data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        }
        return array();
    }

    /**
     * @Route("/edit/{id}", name="category_edit")
     * @Template("BikeDashboardBundle:article/category:edit.html.twig")
     */
    public function editAction(Request $request,$id)
    {
        $categoryService = $this->get('bike.dashboard.service.article_category');
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            try {
                $categoryService->editArticleCategory($id,$data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        } else {
            $category = $categoryService->getArticleCategory($id);
            return ['category'=>$category];
        }
        return array();
    }

}
