<?php

namespace Bike\Dashboard\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;

/**
 * @Route("/recharge")
 */
class RechargeController extends AbstractController
{
    /**
     * @Route("/", name="user_recharge")
     * @Template("BikeDashboardBundle:user/recharge:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

  


}
