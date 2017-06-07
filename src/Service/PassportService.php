<?php

namespace Bike\Dashboard\Service;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Exception\Logic\LogicException;
use Bike\Dashboard\Service\AbstractService;
use Bike\Dashboard\Util\ArgUtil;
use Bike\Dashboard\Db\Dashboard\Passport;

class PassportService extends AbstractService
{
    public function createPassport(array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'username',
            'pwd',
            'repwd',
            'type',
            'create_time',
        ));
        $this->validateUsername($data['username']);
        $this->validatePassword($data['pwd'], $data['repwd']);
        $this->validateType($data['type']);
        if (!$data['create_time']) {
            $data['create_time'] = time();
        }
        $data['pwd'] = $this->hashPassword($data['pwd']);
        $passportDao = $this->getPassportDao();
        return $passportDao->create($data, true);
    }

    public function updatePassport($id,array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'username',
            'pwd',
            'repwd',
            'type',
        ));
        $this->validateUsername($data['username'],$id);

        if ($data['pwd']) {
            $this->validatePassword($data['pwd'], $data['repwd']);    
            $data['pwd'] = $this->hashPassword($data['pwd']);
        } else {
            unset($data['pwd']);
        }
        $this->validateType($data['type']);
        $passportDao = $this->getPassportDao();
        return $passportDao->update($id,$data);        

    }

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

    public function hashPassword($password)
    {
        $options = [
            'cost' => 10,
        ];

        return  password_hash($password, PASSWORD_BCRYPT, $options);
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

    protected function setPassportRequestCache(Passport $passport)
    {
        $this->setRequestCache($this->getPassportRequestCacheKey('id', $passport->getId()), $passport);
        $this->setRequestCache($this->getPassportRequestCacheKey('username', $passport->getUsername()), $passport);
    }

    protected function validateUsername($username,$id = null)
    {
        if (!$username) {
            throw new LogicException('用户名不能为空');
        }
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]{5,18}/', $username)) {
            throw new LogicException('用户名只能是字母，数字或者下划线，首字符不能为数字，长度为6-19个字符');
        }
        $passport = $this->getPassportByUsername($username);


        if ($passport) {
            if ($id !== null) {
                if ($passport->getId() == $id ) {
                    return true;
                }
            }
            throw new LogicException('用户名已存在');
        }
    }

    protected function validatePassword($password, $repassword = null)
    {
        if (!$password) {
            throw new LogicException('密码不能为空');
        }

        $len = strlen($password);
        if ($len < 6) {
            throw new LogicException('密码长度最少6位');
        } elseif ($len > 16) {
            throw new LogicException('密码长度最多16位');
        }

        if ($repassword !== null) {
            if ($password !== $repassword) {
                throw new LogicException('两次输入的密码不一致');
            }
        }
    }

    protected function validateType($type)
    {
        switch ($type) {
            case Passport::TYPE_ADMIN:
                return;
            default:
                throw new LogicException('用户类型不合法');
        }
    }

    protected function getPassportDao()
    {
        return $this->container->get('bike.dashboard.dao.dashboard.passport');
    }
}
