<?php

namespace Bike\Dashboard\Db\Partner;

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

    }

    protected function applyOrder(QueryBuilder $qb, array $order)
    {

    }

    protected function applyGroup(QueryBuilder $qb, array $group)
    {

    }
}
