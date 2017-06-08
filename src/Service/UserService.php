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
            'username',
            'pwd',
            'repwd',
        ));
        $data['id'] = $id;
        $this->validateName($data['name']);
        $userDao = $this->getPrimaryUserDao();
        $userConn = $userDao->getConn();   
        $userService = $this->container->get('bike.dashboard.service.user');
        $userConn->beginTransaction();
        try {          
            $user = new User($data);
            $userDao->save($user);
            $userConn->commit();
        } catch (\Exception $e) {
            $userConn->rollBack();
            throw $e;
        }   
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

    protected function getPrimaryUserDao()
    {
        return $this->container->get('bike.dashboard.dao.primary.user');
    }
}
