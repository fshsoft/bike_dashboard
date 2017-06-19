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

// $data = [
// 	'id' => 99,
// 	'lat' => '33.33',
// 	'lng' => '111.1',
// 	'status' => 5
// ];

// $id = 10000031;
// for ($i=0; $i < 10; $i++) { 
// 	$data = [
// 	'id' =>$id,
// 	'lat' => '33.33'.rand(1000, 9999),
// 	'lng' => '111.1'.rand(10000, 99999),
// 	'status' => 3
// 	];
// 	$id++;
// 	insert($data);
// }

// $filter=[];
// $options = [
// 	    'projection' => ['_id' => 0],
// 	    'sort' => ['_id' => -1],
// 	];
// $rs = find($filter, $options);
// $rs = searchBikes("111.1","33.33",10,array(4,3),$options);
// foreach ($rs as $document) {
//     print_r($document);
// }

// add($data);

// update($data);

// delete(array('_id'=>10000030),1);

// $filter = ['$or'=>array(
// 	array("status"=>1),
// 	array("status"=>2))];
// $options = [
//     'projection' => ['_id' => 0],
//     'sort' => ['_id' => -1],
// ];
// $rs = find($filter, $options);
// foreach ($rs as $document) {
//     print_r($document);
// }

?>