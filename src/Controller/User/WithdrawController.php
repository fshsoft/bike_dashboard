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
 * @Route("/withdraw")
 */
class WithdrawController extends AbstractController
{
    /**
     * @Route("/", name="user_withdraw")
     * @Template("BikeDashboardBundle:user/withdraw:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

  


}
