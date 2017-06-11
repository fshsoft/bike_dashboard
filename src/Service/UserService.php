<?php

namespace Bike\Dashboard\Service;

use Bike\Dashboard\Exception\Debug\DebugException;
use Bike\Dashboard\Exception\Logic\LogicException;
use Bike\Dashboard\Service\AbstractService;
use Bike\Dashboard\Util\ArgUtil;
use Bike\Dashboard\Db\Primary\User as PrimaryUser;


class UserService extends AbstractService
{
    public function editUser($id, array $data)
    {
        $data = ArgUtil::getArgs($data, array(
            'name',
            'mobile',
            'balance',
            'id_no',
            'is_certificated',
            'pwd',
            'repwd',
        ));
        $data['id'] = $id;
        $this->validateName($data['name']);
        $this->validateMobile($data['mobile'],$id);
        if ($data['pwd']) {
            $this->validatePassword($data['pwd'], $data['repwd']);    
            $data['pwd'] = $this->hashPassword($data['pwd']);
        } else {
            unset($data['pwd']);
        }
        $userDao = $this->getPrimaryUserDao();
        return $userDao->update($id,$data); 
    }

    public function searchUser(array $args, $page, $pageNum)
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
        $userDao = $this->getPrimaryUserDao();
        $userList = $userDao->findList('*', $args, $offset, $pageNum);
        $total = $userDao->findNum($args);
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
                'user' => $userList,
            ),
        );
    }

    public function getUser($id)
    {
        $key = 'user.' . $id;
        $user = $this->getRequestCache($key);
        if (!$user) {
            $userDao = $this->getPrimaryUserDao();
            $user = $userDao->find($id);
            if ($user) {
                $this->setRequestCache($key, $user);
            }
        }
        return $user;
    }

    protected function validateName($name)
    {
        if (!$name) {
            throw new LogicException('用户名称不能为空');
        }
        $len = mb_strlen($name);
        if ($len > 20) {
            throw new LogicException('用户名称不能多于20个字符');
        }
    }

    protected function validateMobile($mobile,$id = null)
    {
        if (!$mobile) {
            throw new LogicException('手机号不能为空');
        }
        if (!preg_match('/^1[3|4|5|7|8]\d{9}$/', $mobile)) {
            throw new LogicException('手机号格式不合法');
        }
        $user = $this->getUserByMobile($mobile,$id);
        if ($user) {
            if ($id !== null) {
                if ($user->getId() == $id ) {
                    return true;
                }
            }
            throw new LogicException('手机号已存在');
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

    public function hashPassword($password)
    {
        $options = [
            'cost' => 10,
        ];
        return  password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function getUserByMobile($mobile)
    {
        $key = 'user.mobile.' . $mobile;
        $userDao = $this->getPrimaryUserDao();
        $user = $userDao->find(array(
            'mobile' => $mobile,
        ));
        return $user;
    }

    protected function getPrimaryUserDao()
    {
        return $this->container->get('bike.dashboard.dao.primary.user');
    }
}
