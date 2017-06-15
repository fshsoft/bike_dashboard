<?php

namespace Bike\Dashboard\Mongodb;

use Bike\Dashboard\Exception\Debug\DebugException;

class Connection
{
    protected $host;

    protected $port;

    protected $timeout;

    /**
     * @var Driver
     */
    protected $driver;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    protected function connect()
    {   
        if ($this->driver === null) {     
            $driver = new MongoDB\Driver\Manager("mongodb://".$this->host.":".$this->port);
            if (!$driver) {
                throw new DebugException('MongoDB<' . $this->host . ':' . $this->port . '>无法连接');
            }
            $this->driver = $driver;
        }
    }

    public function __call($method, $args)
    {
        $this->connect();

        return call_user_func_array(array($this->driver, $method), $args);
    }
}
