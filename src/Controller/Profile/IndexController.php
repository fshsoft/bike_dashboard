<?php

namespace Bike\Dashboard\Controller\Profile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Bike\Dashboard\Controller\AbstractController;

/**
 * @Route("/profile")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="profile")
     * @Template("BikeDashboardBundle:profile/index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $id = $user->getId();
        $adminService = $this->get('bike.dashboard.service.admin');
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            try {
                $adminService->editAdmin($id,$data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        } else {
            $admin = $adminService->getAdmin($id);
            return ['admin'=>$admin];
        }
        return array();
    }

   


}
