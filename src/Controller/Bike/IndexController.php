<?php

namespace Bike\Dashboard\Controller\Bike;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;
use Bike\Dashboard\Db\Dashboard\AbstractDao;

/**
 * @Route("/bike")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="bike")
     * @Template("BikeDashboardBundle:bike/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {

    	$passportService = $this->get('bike.dashboard.service.passport');
    	//$list = $passportService->getPassport(1);
    	$user = $this->getUser();
        $session = $request->getSession();
        //print_r($user);
    	//print_r($list);
    	//print_r($passportService);
        return array();
    }

    /**
     * @Route("/new", name="bike_new")
     * @Template("BikeDashboardBundle:bike/index:newbike.html.twig")
     */
    public function newbikeAction()
    {
        
        return array();
    }


}
