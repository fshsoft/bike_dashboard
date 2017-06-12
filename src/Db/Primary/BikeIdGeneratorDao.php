<?php

namespace Bike\Dashboard\Db\Primary;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

use Bike\Dashboard\Db\AbstractDao;
use Bike\Dashboard\Util\ArgUtil;

class BikeIdGeneratorDao extends AbstractDao
{
    protected function parseTable($cond, $dbOp)
    {
        return "`{$this->db}`.`{$this->prefix}bike_id_generator`";
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
