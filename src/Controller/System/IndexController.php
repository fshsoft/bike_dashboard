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
        $adminService = $this->get('bike.dashboard.service.admin');
        $page = $request->query->get('p');
        $pageNum = 10;
        $args = $request->query->all();
        return $adminService->searchAdmin($args, $page, $pageNum);
    }

    /**
     * @Route("/new", name="admin_new")
     * @Template("BikeDashboardBundle:system/index:new.html.twig")
     */
    public function newAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            $adminService = $this->get('bike.dashboard.service.admin');
            try {
                $adminService->createAdmin($data);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        }
        return array();
    }

    /**
     * @Route("/edit/{id}", name="admin_edit")
     * @Template("BikeDashboardBundle:system/index:edit.html.twig")
     */
    public function editAction(Request $request,$id)
    {
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

    /**
     * @Route("/del", name="admin_del")
     * 
     */
    public function delAction(Request $request)
    {
        $adminService = $this->get('bike.dashboard.service.admin');
        if ($request->isMethod('post')) {
            $data = $request->request->all();
            try {
                $adminService->delAdmin($id);
                return $this->jsonSuccess();
            } catch (\Exception $e) {
                return $this->jsonError($e);
            }
        } else {
            $admin = $adminService->getAdmin($id);
        }
        return array();
    }


}
