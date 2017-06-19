<?php

namespace Bike\Dashboard\Mongodb\Dao;

use Bike\Dashboard\Mongodb\Mongo;
use MongoDB\Driver\WriteConcern;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;

abstract class AbstractDao
{

    public function getManage(){
        $mongo = new Mongo();
        $manager = $mongo->connect();
        return $manager; 
    }

    public function insert($data){ 
        $bulk = new BulkWrite();
        $document = array( 
            '_id' => $data['id'],
            'loc' => array( 
              'type' => 'Point', 
              'coordinates' => array(doubleval($data['lng']), doubleval($data['lat'])) 
            ), 
            'status' => $data["status"]
        ); 
        $bulk->insert($document);
        $manager = $this->getManage();
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $manager->executeBulkWrite('bike.bike', $bulk, $writeConcern);
    }

    public function find($filter, $options)
    {
        $manager = $this->getManage();
        $query = new Query($filter, $options);
        $result = $manager->executeQuery('bike.bike', $query);
        return $result;
    }

    public function update(array $data)
    {
        $bulk = new BulkWrite;
        $bulk->update(
            array('_id' => $data['id']),
            array('$set' => array(
                'loc' => array( 
                  'type' => 'Point', 
                  'coordinates' => array(doubleval($data['lng']), doubleval($data['lat'])) 
                ), 
                )
            ),
            array('multi' => false, 'upsert' => false)
        );
        $manager = $this->getManage(); 
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $manager->executeBulkWrite('bike.bike', $bulk, $writeConcern);
    }

    public function delete(array $filter,$limit=1)
    {
        $bulk = new BulkWrite;
        $bulk->delete($filter, ['limit' => $limit]);
        $manager = $this->getManage();
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('bike.bike', $bulk, $writeConcern);
        return $result;
    }
     
    public function searchBikes($lng, $lat, $maxdistance, array $status, $options)
    { 
        if (!$status) {
            $status = array(1,2,3,4,5,6);
        }
        $filter = array( 
            'loc' => array( 
              '$nearSphere' => array( 
                '$geometry' => array( 
                  'type' => 'Point', 
                  'coordinates' => array(doubleval($lng), doubleval($lat)),  
                ), 
                '$maxDistance' => $maxdistance*1000 
              ) 
            ),
            'status' => array('$in' => $status)
        ); 
        $result = $this->find($filter, $options);
        return $result;
    } 

}

