<?php

namespace Bike\Dashboard\Mongodb\Dao;

use Bike\Dashboard\Mongodb\ConnectionAwareTrait;

abstract class AbstractDao
{
    protected $fields = array();

    protected function normalize(array $value)
    {
        return array_merge($this->fields, array_intersect_key($value, $this->fields));
    }

    protected function filter(array $value)
    {
        return array_intersect_key($value, $this->fields);
    }

    public function find($key, $field = null)
    {
        $key = $this->getKey($key);
        if ($field === null) {
            $value = $this->conn->hGetAll($key);
            if ($value) {
                return $this->normalize($value);
            }
        } else {
            return $this->conn->hGet($key, $field);
        }
    }

    public function save($key, array $value, $timestamp = 0, $isTtl = false)
    {
        $key = $this->getKey($key);
        $value = $this->filter($value);
        $this->conn->hMSet($key, $value);
        if ($timestamp) {
            if ($isTtl) {
                $this->conn->expire($key, $timestamp);
            } else {
                $this->conn->expireAt($key, $timestamp);
            }
        }
    }
}

