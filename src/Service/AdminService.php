<?php

namespace Bike\Dashboard\Service;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Exception\Logic\LogicException;
use Bike\Dashboard\Service\AbstractService;
use Bike\Dashboard\Util\ArgUtil;
use Bike\Dashboard\Db\Primary\AdminDao;

class AdminService extends AbstractService
{

    public function createAdmin(array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'username',
            'name',
            'pwd',
            'repwd',
            'create_time',
        ));
        $this->validateUsername($data['username']);
        $this->validatePassword($data['pwd'], $data['repwd']);
        if (!$data['create_time']) {
            $data['create_time'] = time();
        }
        $data['pwd'] = $this->hashPassword($data['pwd']);
        $adminDao = $this->getPrimaryAdminDao();
        return $adminDao->create($data, true);
    }

    public function editAdmin($id,array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'username',
            'pwd',
            'repwd',
            'name',
        ));
        $this->validateUsername($data['username'],$id);

        if ($data['pwd']) {
            $this->validatePassword($data['pwd'], $data['repwd']);    
            $data['pwd'] = $this->hashPassword($data['pwd']);
        } else {
            unset($data['pwd']);
        }
        $adminDao = $this->getPrimaryAdminDao();
        return $adminDao->update($id,$data);        

    }

    public function searchAdmin(array $args, $page, $pageNum)
    {
        $page = intval($page);
        $pageNum = intval($pageNum);
        if ($page < 1) {
            $page = 1;
        }
        if ($pageNum < 1) {
            $pageNum = 1;
        }
        $offset = ($page - 1) * $pageNum;
        $adminDao = $this->getPrimaryAdminDao();
        $adminList = $adminDao->findList('*', $args, $offset, $pageNum);
        //print_r($adminList);die;
        $total = $adminDao->findNum($args);
        if ($total) {
            $totalPage = ceil($total / $pageNum);
            if ($page > $totalPage) {
                $page = $totalPage;
            }
        } else {
            $totalPage = 1;
            $page = 1;
        }
        return array(
            'page' => $page,
            'totalPage' => $totalPage,
            'pageNum' => $pageNum,
            'total' => $total,
            'list' => array(
                'admin' => $adminList,
            ),
        );
    }

    public function getAdmin($id)
    {
        $key = $this->getAdminRequestCacheKey('id', $id);
        $admin = $this->getRequestCache($key);
        if (!$admin) {
            $adminDao = $this->getPrimaryAdminDao();
            $admin = $adminDao->find($id);
            if ($admin) {
                $this->setAdminRequestCache($admin);
            }
        }
        return $admin;
    }

    public function getAdminByUsername($username)
    {
        $key = $this->getAdminRequestCacheKey('username', $username);
        $admin = $this->getRequestCache($key);
        if (!$admin) {
            $adminDao = $this->getPrimaryAdminDao();
            $admin = $adminDao->find(array('username' => $username));
            if ($admin) {
                $this->setAdminRequestCache($admin);
            }
        }
        return $admin;
    }

    public function hashPassword($password)
    {
        $options = [
            'cost' => 10,
        ];

        return  password_hash($password, PASSWORD_BCRYPT, $options);
    }

    protected function setAdminRequestCache($admin)
    {
        $this->setRequestCache($this->getAdminRequestCacheKey('id', $admin->getId()), $admin);
        $this->setRequestCache($this->getAdminRequestCacheKey('username', $admin->getUsername()), $admin);
    }

    protected function  getAdminRequestCacheKey($type, $value)
    {
        switch ($type) {
            case 'id':
            case 'username':
                return 'admin.' . $type . '.' . $value;
        }
        throw new DebugException('非法的admin request cache key');
    }

    protected function validateUsername($username,$id = null)
    {
        if (!$username) {
            throw new LogicException('用户名不能为空');
        }
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]{5,18}/', $username)) {
            throw new LogicException('用户名只能是字母，数字或者下划线，首字符不能为数字，长度为6-19个字符');
        }
        $admin = $this->getAdminByUsername($username);
        if ($admin) {
            if ($id !== null) {
                if ($admin->getId() == $id ) {
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

    protected function validateName($name)
    {
        if (!$name) {
            throw new LogicException('管理员名称不能为空');
        }
        $len = mb_strlen($name);
        if ($len > 20) {
            throw new LogicException('管理员名称不能多于20个字符');
        }
    }

    protected function getPrimaryAdminDao()
    {
        return $this->container->get('bike.dashboard.dao.primary.admin');
    }
}
