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
 * @Route("/version")
 */
class VersionController extends AbstractController
{
    /**
     * @Route("/", name="system_version")
     * @Template("BikeDashboardBundle:system/version:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

  


}
