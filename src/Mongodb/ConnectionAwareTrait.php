<?php

namespace Bike\Dashboard\Mongodb;

trait ConnectionAwareTrait
{
    protected $conn;

    public function setConn(Connection $conn)
    {
        $this->conn = $conn;
        print_r($conn);die;
        return $this;
    }
}
