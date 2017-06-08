<?php

namespace Bike\Dashboard\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Bike\Dashboard\Db\Dashboard\Passport;


/**
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Template("BikeDashboardBundle:index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        //die("sss");
        return array();
    }

    /**
     * @Route("/login", name="login")
     * @Template("BikeDashboardBundle:index:login.html.twig")
     */
    public function loginAction(Request $request)
    {
        return array();
    }


    /**
     * @Route("/test")
     */
    public function testAction(Request $request)
    {
        $adminService = $this->get('bike.dashboard.service.admin');
        $data = array(
            'name' => '管理员1',
            'username' => 'bikebox1',
            'pwd' => '789789',
            'repwd' => '789789',
        );
        $adminService->createAdmin($data);
    }

}
