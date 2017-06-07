<?php

namespace Bike\Dashboard\Twig;

use Bike\Dashboard\Db\Dashboard\Passport;

class PassportExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('bike_dashboard_passport_display_name', array($this, 'getDisplayName')),
            new \Twig_SimpleFunction('bike_dashboard_passport_title', array($this, 'getTitle')),
        );
    }

    public function getDisplayName($id)
    {
        $passportService = $this->container->get('bike.dashboard.service.passport');
        $passport = $passportService->getPassport($id);
        $adminService = $this->container->get('bike.dashboard.service.admin');
        $admin = $adminService->getAdmin($id);
        if ($admin) {
            return $admin->getName();
        }
          
    }

    public function getTitle($id)
    {
        $passportService = $this->container->get('bike.dashboard.service.passport');
        $passport = $passportService->getPassport($id);
        $adminService = $this->container->get('bike.dashboard.service.admin');
        $admin = $adminService->getAdmin($id);
        if ($admin) {
            return '管理员';
        }
    }
}
