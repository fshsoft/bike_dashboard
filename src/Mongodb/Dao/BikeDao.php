<?php

namespace Bike\Dashboard\Mongodb\Dao;

use Bike\Dashboard\Util\ArgUtil;

class BikeDao extends AbstractDao
{
    protected static $fields = array( 
    	'_id' => null,
		'loc' => array( 
		  'type' => 'Point', 
		  'coordinates' => null 
		), 
		'status' => null
	); 
}
