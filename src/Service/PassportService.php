<?php

namespace Bike\Dashboard\Service;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Service\AbstractService;

class PassportService extends AbstractService
{
    public function getPassport($id)
    {
        $key = $this->getPassportRequestCacheKey('id', $id);
        $passport = $this->getRequestCache($key);
        if (!$passport) {
            $passportDao = $this->getPassportDao();
            $passport = $passportDao->find($id);
            if ($passport) {
                $this->setPassportRequestCache($passport);
            }
        }
        return $passport;
    }

    public function getPassportByUsername($username)
    {
        $key = $this->getPassportRequestCacheKey('username', $username);
        $passport = $this->getRequestCache($key);
        if (!$passport) {
            $passportDao = $this->getPassportDao();
            $passport = $passportDao->find(array('username' => $username));
            if ($passport) {
                $this->setPassportRequestCache($passport);
            }
        }
        return $passport;
    }

    protected function getPassportRequestCacheKey($type, $value)
    {
        switch ($type) {
            case 'id':
            case 'username':
                return 'passport.' . $type . '.' . $value;
        }
        throw new DebugException('非法的passport request cache key');
    }

    protected function getPassportDao()
    {
        return $this->container->get('bike.dashboard.dao.dashboard.passport');
    }
}
