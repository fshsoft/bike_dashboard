<?php

namespace Bike\Dashboard\Db\Dashboard;

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
            'id', 
            'name',
        ));
        if ($where['id']) {
            $qb->andWhere('id = ' . $qb->createNamedParameter($where['id']));
        }

        if ($where['name']) {
            $qb->andWhere('name like :likename')->setParameter(':likename','%'.$where['name'].'%');
        }
    }

    protected function applyOrder(QueryBuilder $qb, array $order)
    {

    }

    protected function applyGroup(QueryBuilder $qb, array $group)
    {

    }
}
