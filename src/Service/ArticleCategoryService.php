<?php

namespace Bike\Dashboard\Service;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Exception\Logic\LogicException;
use Bike\Dashboard\Service\AbstractService;
use Bike\Dashboard\Util\ArgUtil;

use Bike\Dashboard\Db\Primary\ArticleCategory;


class ArticleCategoryService extends AbstractService
{

    public function createArticleCategory(array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'name',
            'sort',
        ));
        $categoryDao = $this->getPrimaryArticleCategoryDao();
        return $categoryDao->create($data, true);
    }

    public function editArticleCategory($id,array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'name',
            'sort',
        ));
        $categoryDao = $this->getPrimaryArticleCategoryDao();
        return $categoryDao->update($id,$data);        
    }

    public function searchArticleCategory(array $args, $page, $pageNum)
    {
        $page = intval($page);
        $pageNum = intval($pageNum);
        if ($page < 1) {
            $page = 1;
        }
        if ($pageNum < 1) {
            $pageNum = 1;
        }
        $offset = ($page - 1) * $pageNum;
        $categoryDao = $this->getPrimaryArticleCategoryDao();
        $categoryList = $categoryDao->findList('*', $args, $offset, $pageNum);
        $total = $categoryDao->findNum($args);
        if ($total) {
            $totalPage = ceil($total / $pageNum);
            if ($page > $totalPage) {
                $page = $totalPage;
            }
        } else {
            $totalPage = 1;
            $page = 1;
        }
        return array(
            'page' => $page,
            'totalPage' => $totalPage,
            'pageNum' => $pageNum,
            'total' => $total,
            'list' => array(
                'category' => $categoryList,
            ),
        );
    }

    public function getArticleCategory($id)
    {
        $key = 'article_category.' . $id;
        $category = $this->getRequestCache($key);
        if (!$category) {
            $categoryDao = $this->getPrimaryArticleCategoryDao();
            $category = $categoryDao->find($id);
            if ($category) {
                $this->setRequestCache($key, $category);
            }
        }
        return $category;
    }

    protected function getPrimaryArticleCategoryDao()
    {
        return $this->container->get('bike.dashboard.dao.primary.article_category');
    }
}
