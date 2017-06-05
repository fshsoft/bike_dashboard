<?php

namespace Bike\Dashboard\Controller\Operation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;

/**
 * @Route("/operaion")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="operaion")
     * @Template("BikeDashboardBundle:operaion:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

  


}
