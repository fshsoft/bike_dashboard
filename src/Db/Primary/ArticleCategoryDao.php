<?php

namespace Bike\Dashboard\Db\Primary;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

use Bike\Dashboard\Db\AbstractDao;
use Bike\Dashboard\Util\ArgUtil;

class ArticleCategoryDao extends AbstractDao
{
    protected function parseTable($cond, $dbOp)
    {
        return "`{$this->db}`.`{$this->prefix}article_category`";
    }

    protected function applyWhere(QueryBuilder $qb, array $where, $dbOp)
    {

    }

    protected function applyOrder(QueryBuilder $qb, array $order)
    {
        if (!$order) {
            $qb->orderBy('sort', 'asc');
        }
    }

    protected function applyGroup(QueryBuilder $qb, array $group)
    {

    }
}
