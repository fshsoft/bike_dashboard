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
 * @Route("/coupon")
 */
class CouponController extends AbstractController
{
    /**
     * @Route("/", name="operation_coupon")
     * @Template("BikeDashboardBundle:operation/coupon:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

  


}
