<?php

namespace Bike\Dashboard\Db\Dashboard;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

use Bike\Dashboard\Db\AbstractDao;
use Bike\Dashboard\Util\ArgUtil;

class PassportDao extends AbstractDao
{
    protected function parseTable($cond, $dbOp)
    {
        return "`{$this->db}`.`{$this->prefix}passport`";
    }

    protected function applyWhere(QueryBuilder $qb, array $where, $dbOp)
    {

    }

    protected function applyOrder(QueryBuilder $qb, array $order)
    {
        if (!$order) { // 默认id倒序 
            $qb->orderBy('id', 'DESC');
        }
    }

    protected function applyGroup(QueryBuilder $qb, array $group)
    {

    }
}
