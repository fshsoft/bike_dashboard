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
        $where = ArgUtil::getArgs($where, array(
            'id.in',
            'username',
            'type',
        ));
        if ($where['id.in']) {
            $qb->andWhere('id IN (' . $qb->createNamedParameter($where['id.in'], Connection::PARAM_INT_ARRAY) . ')');
        }
        if ($where['username']) {
            $qb->andWhere('username = ' . $qb->createNamedParameter($where['username']));
        }
        if ($where['type']) {
            $qb->andWhere('type = ' . $qb->createNamedParameter($where['type']));
        }
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
