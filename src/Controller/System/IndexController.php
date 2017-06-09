<?php

namespace Bike\Dashboard\Controller\System;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;

/**
 * @Route("/system")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="system_admin")
     * @Template("BikeDashboardBundle:system/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

  


}
