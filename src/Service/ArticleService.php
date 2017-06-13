<?php

namespace Bike\Dashboard\Service;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Exception\Logic\LogicException;
use Bike\Dashboard\Service\AbstractService;
use Bike\Dashboard\Util\ArgUtil;

use Bike\Dashboard\Db\Primary\Article;


class ArticleService extends AbstractService
{

    public function createArticle(array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'category_id',
            'title',
            'sub_title',
            'content',
            'status',
            'sort',
            'picpath',
            'create_time',
        ));
        $articleDao = $this->getPrimaryArticleDao();
        return $articleDao->create($data, true);
    }

    public function editArticle($id,array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'category_id',
            'title',
            'sub_title',
            'content',
            'status',
            'sort',
            'picpath',
            'create_time',
        ));
        $articleDao = $this->getPrimaryArticleDao();
        return $articleDao->update($id,$data);        
    }

    public function searchArticle(array $args, $page, $pageNum)
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
        $articleDao = $this->getPrimaryArticleDao();
        $articleList = $articleDao->findList('*', $args, $offset, $pageNum);
        print_r($articleList);die;
        $total = $articleDao->findNum($args);
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
                'article' => $articleList,
            ),
        );
    }

    protected function getPrimaryArticleDao()
    {
        return $this->container->get('bike.dashboard.dao.primary.article');
    }
}
