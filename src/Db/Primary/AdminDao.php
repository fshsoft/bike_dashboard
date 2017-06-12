<?php

namespace Bike\Dashboard\Db\Primary;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

use Bike\Dashboard\Db\AbstractDao;
use Bike\Dashboard\Util\ArgUtil;

class AdminDao extends AbstractDao
{
    protected function parseTable($cond, $dbOp)
    {
        return "`{$this->db}`.`{$this->prefix}admin`";
    }

    protected function applyWhere(QueryBuilder $qb, array $where, $dbOp)
    {
        $where = ArgUtil::getArgs($where, array(
            'username',
            'name',
            'id.in',
        ));
        if ($where['name']) {
            $qb->andWhere('name like :likename')->setParameter(':likename','%'.$where['name'].'%');
        }
        if ($where['username']) {
            $qb->andWhere('username = ' . $qb->createNamedParameter($where['username']));
        }
        if ($where['id.in']) {
            $qb->andWhere('id IN (' . $qb->createNamedParameter($where['id.in'], Connection::PARAM_INT_ARRAY) . ')');
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
