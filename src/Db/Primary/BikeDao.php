<?php

namespace Bike\Dashboard\Db\Primary;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

use Bike\Dashboard\Db\AbstractDao;
use Bike\Dashboard\Util\ArgUtil;
class BikeDao extends AbstractDao
{
    protected function parseTable($cond, $dbOp)
    {
        return "`{$this->db}`.`{$this->prefix}bike`";
    }

    protected function applyWhere(QueryBuilder $qb, array $where, $dbOp)
    {
        $where = ArgUtil::getArgs($where, array(
            'id'
        )); 
        if ($where['id']) {
            $qb->andWhere('id = ' . $qb->createNamedParameter($where['id']));
        }
    }

    protected function applyOrder(QueryBuilder $qb, array $order)
    {
        if ($order) {
            foreach ($order as $col => $sort) {
                $qb->addOrderBy($col, $sort);
            }
        }
    }

    protected function applyGroup(QueryBuilder $qb, array $group)
    {

    }
}
