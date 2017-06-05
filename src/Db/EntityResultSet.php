<?php

namespace Bike\Dashboard\Db;

use Bike\Dashboard\Exception\Debug\DebugException;

class EntityResultSet implements EntityResultSetInterface, \Countable, \Iterator, \ArrayAccess
{
    private $data = array();

    private $container = array();

    private $entityClass;

    final public function __construct($entityClass, array $data)
    {
        $this->entityClass = $entityClass;

        $this->fromArray($data);
    }

    public function count()
    {
        return count($this->data);
    }

    public function rewind()
    {
        reset($this->data);
    }

    function current()
    {
        return $this->offsetGet($this->key());
    }

    function key()
    {
        return key($this->data);
    }

    function next()
    {
        next($this->data);
    }

    function valid()
    {
        return $this->key() !== null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_array($value)) {
            $v = $value;
        } elseif (is_object($value) && $value instanceof EntityInterface) {
            $v = $value->toArray();
        } else {
            throw new DebugException('$value必须是数组或者是EntityInterface');
        }

        if (is_null($offset)) {
            $this->data[] = $v;
        } else {
            $this->data[$offset] = $v;
            unset($this->container[$offset]);
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset], $this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->container[$offset])) {
            return $this->container[$offset];
        }

        if (isset($this->data[$offset])) {
            $entity = new $this->entityClass($this->data[$offset]);
            $this->container[$offset] = $entity;

            return $this->container[$offset];
        }
    }

    /**
     * container的元素值可能改变，$data数据可能过时，所以要对cotainer的元素toArray
     *
     * @return array
     */
    public function toArray()
    {
        foreach ($this->container as $k => $v) {
            $this->data[$k] = $v->toArray();
        }

        return $this->data;
    }

    public function toArrayForInsert()
    {
        $data = array();

        foreach ($this->data as $k => $v) {
            $entity = $this->offsetGet($k);
            $data[$k] = $entity->toArrayForInsert();
        }

        return $data;
    }

    public function toArrayForUpdate()
    {
        $data = array();
        foreach ($this->data as $k => $v) {
            $entity = $this->offsetGet($k);
            $data[$k] = $entity->toArrayForUpdate();
        }

        return $data;
    }

    public function fromArray(array $data)
    {
        $this->data = $data;
        $this->container = array();
    }
}
