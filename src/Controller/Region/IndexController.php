<?php

namespace Bike\Dashboard\Controller\Region;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;
use Bike\Dashboard\Mongodb\Connection;

/**
 * @Route("/region")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="region")
     * @Template("BikeDashboardBundle:region/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
    	$c = new Connection('127.0.0.1','27017');
    	
    	$bulk = new MongoDB\Driver\BulkWrite;
    	$filter = ['x' => ['$gt' => 0]];
		$options = [
		    'projection' => ['_id' => 0],
		    'sort' => ['x' => -1],
		];


		$query = new MongoDB\Driver\Query($filter, $options);
		$cursor = $manager->executeQuery('test.sites', $query);

		foreach ($cursor as $document) {
		    print_r($document);
		}

        return array();
    }

  


}
