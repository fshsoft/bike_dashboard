<?php


function getManage(){
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017"); 
	return $manager; 
}

function insert($data){ 
	$bulk = new \MongoDB\Driver\BulkWrite;
	$document = array( 
		'_id' => $data['id'],
		'loc' => array( 
		  'type' => 'Point', 
		  'coordinates' => array(doubleval($data['lng']), doubleval($data['lat'])) 
		), 
		'status' => $data["status"]
	); 
	$bulk->insert($document);
	$manager = getManage();
	$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
	$result = $manager->executeBulkWrite('bike.bike', $bulk, $writeConcern);
	return $result; 
}

function find($filter, $options)
{
	$manager = getManage();
	$query = new MongoDB\Driver\Query($filter, $options);
	$result = $manager->executeQuery('bike.bike', $query);
	return $result;
}

function update(array $data)
{
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->update(
	    array('_id' => $data['id']),
	    array('$set' => array(
			'loc' => array( 
			  'type' => 'Point', 
			  'coordinates' => array(doubleval($data['lng']), doubleval($data['lat'])) 
			), 
			'status' => $data['status']
			)
		),
	    array('multi' => false, 'upsert' => false)
	);
	$manager = getManage(); 
	$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
	$result = $manager->executeBulkWrite('bike.bike', $bulk, $writeConcern);
	return $result;
}

function delete(array $filter,$limit=1)
{
	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->delete($filter, ['limit' => $limit]);
	$manager = getManage();
	$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
	$result = $manager->executeBulkWrite('bike.bike', $bulk, $writeConcern);
	return $result;
}
 
function searchBikes($lng, $lat, $maxdistance, array $status, $options)
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
	$result = find($filter, $options);
	return $result;
} 


?>