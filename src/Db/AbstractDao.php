<?php

namespace Bike\Dashboard\Db;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Util\ArgUtil;
use Bike\Dashboard\Util\NamingUtil;

abstract class AbstractDao implements DaoInterface
{
    const DB_OP_SELECT = 1;
    const DB_OP_INSERT = 2;
    const DB_OP_UPDATE = 3;
    const DB_OP_DELETE = 4;
    const DB_OP_REPLACE = 5;

    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var string
     */
    protected $db;

    /**
     * @var string
     */
    protected $prefix;

    protected $entityResultSetClass;

    protected $entityClass;

    final public function __construct(Connection $conn, $db, $prefix, $entityClass, 
        $entityResultSetClass = 'Bike\Dashboard\Db\EntityResultSet')
    {
        $this->conn = $conn;
        $this->db = $db;
        $this->prefix = $prefix;

        if (!is_subclass_of($entityClass, 'Bike\Dashboard\Db\EntityInterface')) {
            throw new DebugException(sprintf('%s必须实现Bike\Dashboard\Db\EntityInterface', $entityClass));
        }
        if (!class_exists($entityClass)) {
            throw new DebugException(sprintf('%s不存在', $entityClass));
        }
        $this->entityClass = $entityClass;

        if (!is_subclass_of($entityResultSetClass, 'Bike\Dashboard\Db\EntityResultSetInterface')) {
            throw new DebugException(sprintf('%s必须实现Bike\Dashboard\Db\EntityResultSetInterface', $entityResultSetClass));
        }
        if (!class_exists($entityResultSetClass)) {
            throw new DebugException(sprintf('%s不存在', $entityResultSetClass));
        }
        $this->entityResultSetClass = $entityResultSetClass;
    }

    public function getConn()
    {
        return $this->conn;
    }

    /**
     * 表名必须带上数据库名，因为可能共用一个数据库连接
     *
     * @return string
     */
    abstract protected function parseTable($cond, $dbOp);

    /**
     * 此方法用于每个表更方便的集中管理索引
     * 每个字段都需要isset出来
     */
    abstract protected function applyWhere(QueryBuilder $qb, array $where, $dbOp);

    abstract protected function applyOrder(QueryBuilder $qb, array $order);

    abstract protected function applyGroup(QueryBuilder $qb, array $group);

    public function create($data, $lastInsertId = false)
    {
        // 过滤data中不需要的字段
        if (is_array($data)) {
            $data = $this->entity($data);
        }
        if ($data instanceof EntityInterface) {
            $data = $data->toArrayForInsert();
        } else {
            throw new DebugException('参数只能是数组或者EntityInterface');
        }

        $table = $this->parseTable($data, self::DB_OP_INSERT);
        $qb = $this->conn->createQueryBuilder()->insert($table);
        foreach ($data as $k => $v) {
            $qb->setValue($k, $qb->createNamedParameter($v));
        }
        $result = $qb->execute();
        if ($lastInsertId) {
            $result = $this->conn->lastInsertId(); 
        }
        return $result;
    }

    /**
     * 使用此方法，请确认mysql server的max_allowed_packet足够大
     *
     * @param array $dataList
     *
     * 需要保证$dataList中每个元素的字段都一致
     *
     * array(
        0 => array(
     *          col => value
     *      )
     * )
     *
     * EntityResultSet
     *
     * @return int 影响的行数
     */
    public function batchCreate($dataList)
    {
        if (!is_array($dataList) && !$dataList instanceof EntityResultSet) {
            throw new DebugException('参数只能是数组或者EntityResultSet');
        }
        // 过滤数据
        $data = array();
        foreach ($dataList as $v) {
            if (is_array($v)) {
                $v = $this->entity($v);
            }
            if (!$v instanceof EntityInterface) {
                throw new DebugException('参数中的数据只能是数组或者EntityInterface');
            }
            $data[] = $v->toArrayForInsert();
        }

        $sqlParts = array();
        $params = array();
        $cols = array();
        $index = 0;

        foreach ($data as $v) {
            // 第一次循环取得table
            if ($index === 0) {
                $sql = 'INSERT INTO ' . $this->parseTable($v, self::DB_OP_INSERT) . ' ';
                $index++;
            }

            // 第一次初始化col list
            if (!$cols) {
                $cols = array_keys($v);
                $sql .= ' (' . implode(',', $cols) . ') VALUES ';
            }

            $valueParts = array();
            foreach ($v as $vv) {
                $params[] = $vv;
                $valueParts[] = '?';
            }

            $sqlParts[] = '(' . implode(',', $valueParts) . ')';
        }

        $sql .= implode(',', $sqlParts);

        return $this->conn->executeUpdate($sql, $params);
    }

    /**
     * doctrine dbal不支持replace into，需要自己写sql
     *
     * replace into 使用不当，容易造成数据被非正常覆盖，且把两种业务逻辑用同一种方式更新数据，极易造成一些很难被发现的bug。
     *
     * 除非全量更新，否则不要用replace into
     *
     * @return int 影响的行数
     */
    public function save($data, $lastInsertId = false)
    {
        // 过滤data中不需要的字段
        if (is_array($data)) {
            $data = $this->entity($data);
        }
        if ($data instanceof EntityInterface) {
            $data = $data->toArrayForInsert();
        } else {
            throw new DebugException('参数只能是数组或者EntityInterface');
        }

        $table = $this->parseTable($data, self::DB_OP_REPLACE);
        $sql = 'REPLACE INTO ' . $table . ' SET ';
        $sqlParts = array();

        foreach ($data as $k => $v) {
            $sqlParts[] = $k . ' = :' . $k;
        }

        $sql .= implode(',', $sqlParts);
        $result = $this->conn->executeUpdate($sql, $data);
        if ($lastInsertId) {
            $result = $this->conn->lastInsertId();
        }
        return $result;
    }

    /**
     * 使用此方法，请确认mysql server的max_allowed_packet足够大
     *
     * @param array $dataList
     *
     * 需要保证$dataList中每个元素的字段都一致
     *
     * array(
        0 => array(
     *          col => value
     *      )
     * )
     *
     * EntityResultSet
     *
     * @return int 影响的行数
     */
    public function batchSave($dataList)
    {
        if (!is_array($dataList) && !$dataList instanceof EntityResultSet) {
            throw new DebugException('参数只能是数组或者EntityResultSet');
        }
        // 过滤数据
        $data = array();
        foreach ($dataList as $v) {
            if (is_array($v)) {
                $v = $this->entity($v);
            }
            if (!$v instanceof EntityInterface) {
                throw new DebugException('参数中的数据只能是数组或者EntityInterface');
            }
            $data[] = $v->toArrayForInsert();
        }

        $sqlParts = array();
        $params = array();
        $cols = array();
        $index = 0;

        foreach ($data as $v) {
            // 第一次，解析table
            if ($index === 0) {
                $table = $this->parseTable($v, self::DB_OP_REPLACE);
                $sql = 'REPLACE INTO ' . $table;
                $index++; 
            }

            if (!$cols) {
                $cols = array_keys($v);
                $sql .= ' (' . implode(',', $cols) . ') VALUES ';
            }

            $valueParts = array();
            foreach ($v as $vv) {
                $params[] = $vv;
                $valueParts[] = '?';
            }

            $sqlParts[] = '(' . implode(',', $valueParts) . ')';
        }

        $sql .= implode(',', $sqlParts);

        return $this->conn->executeUpdate($sql, $params);
    }

    public function update($where, $data, array $exprs = array())
    {
        // 过滤data中不需要的字段
        if (is_array($data)) {
            $data = $this->entity($data);
        }
        if ($data instanceof EntityInterface) {
            $data = $data->toArrayForUpdate();
        } else {
            throw new DebugException('参数只能是数组或者EntityInterface');
        }

        $qb = $this->conn->createQueryBuilder();
        
        if (!is_array($where)) {
            $qb->where($this->getPrimaryKey() . '=' . $qb->createNamedParameter($where));
        } else {
            $this->applyWhere($qb, $where, self::DB_OP_UPDATE);
        }
        foreach ($data as $k => $v) {
            $qb->set($k, $qb->createNamedParameter($v));
        }
        $exprs = ArgUtil::getArgs($exprs, [
            'incres',
        ]);
        if (is_array($exprs['incres']) && $exprs['incres']) {
            foreach ($exprs['incres'] as $col => $incre) {
                if (is_numeric($incre)) {
                    $qb->set($col, $col . '+ (' . $incre . ')');
                }
            }
        }
        
        return $qb->update($this->parseTable($where, self::DB_OP_UPDATE))->execute();
    }

    public function delete($where)
    {
        $qb = $this->conn->createQueryBuilder();
        if (!is_array($where)) {
            $qb->where($this->getPrimaryKey() . '=' . $qb->createNamedParameter($where));
        } else {
            $this->applyWhere($qb, $where, self::DB_OP_DELETE);
        }
        return $qb->delete($this->parseTable($where, self::DB_OP_DELETE))->execute();
    }

    /**
     * 单条记录，默认可以获取全部字段
     */
    public function find($where, $cols = '*')
    {
        $qb = $this->conn->createQueryBuilder();
        if (!is_array($where)) {
            $qb->where($this->getPrimaryKey() . '=' . $qb->createNamedParameter($where));
        } else {
            $this->applyWhere($qb, $where, self::DB_OP_SELECT);
        }
        $result = $qb
            ->select($this->parseCols($cols))
            ->from($this->parseTable($where, self::DB_OP_SELECT))
            ->execute()
            ->fetch();
        if ($result) {
            return $this->entity($result);
        }
    }

    /**
     * 多条记录必须显示地带上字段
     *
     * @param array $args
     * [
     *  'orders' => array(),
     *  'groups' => array(),
     *  ]
     */
    public function findList($cols, array $where, $offset, $limit, 
        array $order = array(), array $group = array())
    {
        $result = $this->findRawList($cols, $where, $offset, $limit, $order, $group);
        if ($result) {
            return $this->entityResultSet($result);
        }
    }

    /**
     * findMap[AsBlaBlaBla]
     */
    public function __call($method, $args)
    {
        $mapCol = null;
        if ($method == 'findMap') {
            $mapCol = $this->getPrimaryKey();
        } elseif (($pos = strpos($method, 'findMapAs')) !== false) {
            $mapCol = NamingUtil::studlyCaseToUnderscore(substr($method, $pos + 9));
        }
        if ($mapCol !== null) {
            $result = call_user_func_array([$this, 'findRawList'], $args);
            if ($result) {
                $map = array();
                foreach ($result as $row) {
                    $map[$row[$mapCol]] = $row;
                }
                return $this->entityResultSet($map);
            }
        }
    }

    protected function findRawList($cols, array $where, $offset, $limit, 
        array $order = array(), array $group = array())
    {
        $qb = $this->conn->createQueryBuilder();
        $this->applyWhere($qb, $where, self::DB_OP_SELECT);
        // $offset和$limit同为0时，才表示取全部记录，不分页
        if ($offset != 0 || $limit != 0) {
            $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit);
        }
        //可能会有默认的排序方式，无论是否为空，都调用一次
        //由具体的dao来决定
        $this->applyOrder($qb, $order);
        if ($group) {
            $this->applyGroup($qb, $group);
        }
        return $qb
            ->select($this->parseCols($cols))
            ->from($this->parseTable($where, self::DB_OP_SELECT))
            ->execute()
            ->fetchAll();
    }

    public function findNum(array $where, $col = '*', array $group = array())
    {
        $qb = $this->conn->createQueryBuilder();
        $this->applyWhere($qb, $where, self::DB_OP_SELECT);
        if ($group) {
            $this->applyGroup($qb, $group);
        }
        $result = $qb
            ->select('COUNT(' . $col . ') AS num')
            ->from($this->parseTable($where, self::DB_OP_SELECT))
            ->execute()
            ->fetch();
        return $result['num'];
    }

    /**
     * 没做字段的反引号操作，请确保字段中没有mysql保留字
     *
     * 约定优于配置
     */
    protected function parseCols($cols)
    {
        if (!$cols || $cols == '*') {
            return '*';
        } 
        if (is_array($cols)) {
            $cols = implode(',', $cols);
        }
        return $cols;
    }

    protected function getPrimaryKey()
    {
        return call_user_func(array($this->entityClass, 'getPrimaryKey'));
    }

    protected function entity(array $data)
    {
        return new $this->entityClass($data);
    }

    protected function entityResultSet(array $data)
    {
        return new $this->entityResultSetClass($this->entityClass, $data);
    }
}
