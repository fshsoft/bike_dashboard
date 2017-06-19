<?php

namespace Bike\Dashboard\Mongodb;

use MongoDB\Driver\Manager;

use Bike\Dashboard\Exception\Debug\DebugException;

class Mongo
{
    public function connect()
    {   
        $manager = new Manager("mongodb://localhost:27017"); 
        return $manager; 
    }

}
