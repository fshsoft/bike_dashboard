<?php

namespace Bike\Dashboard\Mongodb\Dao;

use Bike\Dashboard\Mongodb\Connection;

abstract class AbstractDao
{
    private $conn = null;

    private $db = null;

    private function __construct() {  
        $this->conn = new Connection('127.0.0.1','27017');
        $this->db   = "bike";  
    } 

    public function insert($collname, array $documents, array $writeOps = []) {  
        $cmd = [  
            "insert"    => $collname,  
            "documents" => $documents,  
        ];  
        $cmd += $writeOps;  
        return $this->command($cmd);  
    }  
      
   
    public function del($collname, array $deletes, array $writeOps = []) {  
        foreach($deletes as &$_){  
            if(isset($_["q"]) && !$_["q"]){  
                $_["q"] = (Object)[];  
            }  
            if(isset($_["limit"]) && !$_["limit"]){  
                $_["limit"] = 0;  
            }  
        }  
        $cmd = [  
            "delete"    => $collname,  
            "deletes"   => $deletes,  
        ];  
        $cmd += $writeOps;  
        return $this->command($cmd);  
    }  
      
 
    public function update($collname, array $updates, array $writeOps = []) {  
        $cmd = [  
            "update"    => $collname,  
            "updates"   => $updates,  
        ];  
        $cmd += $writeOps;  
        return $this->command($cmd);  
    }  
      
 
    public function query($collname, array $filter, array $writeOps = []){  
        $cmd = [  
            "find"      => $collname,  
            "filter"    => $filter  
        ];  
        $cmd += $writeOps;  
        return $this->command($cmd);  
    }  
  
    public function command(array $param) {  
        $cmd = new MongoDB\Driver\Command($param);  
        return $this->conn->executeCommand($this->db, $cmd);  
    }  
  
    public function getMongoManager() {  
        return $this->conn;  
    } 


}

