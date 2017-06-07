<?php

namespace Bike\Dashboard\Controller\Partner;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;

/**
 * @Route("/partner")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="partner")
     * @Template("BikeDashboardBundle:partner/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

  


}
