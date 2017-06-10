<?php

namespace Bike\Dashboard\Twig;

use Bike\Dashboard\Db\Primary\Admin;

class AdminExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('bike_dashboard_admin_display_name', array($this, 'getDisplayName')),
            new \Twig_SimpleFunction('bike_dashboard_admin_title', array($this, 'getTitle')),
        );
    }

    public function getDisplayName($id)
    {
        $adminService = $this->container->get('bike.dashboard.service.admin');
        $admin = $adminService->getAdmin($id);
        if ($admin) {
            return $admin->getName();
        }
          
    }

    public function getTitle($id)
    {
        $adminService = $this->container->get('bike.dashboard.service.admin');
        $admin = $adminService->getAdmin($id);
        if ($admin) {
            return '百宝';
        }
    }
}
