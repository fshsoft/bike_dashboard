<?php

namespace Bike\Dashboard\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

use Bike\Dashboard\Db\Dashboard\Passport;

class User extends Passport implements UserInterface
{

    protected $roleMap = array(
        Passport::TYPE_ADMIN => 'ROLE_ADMIN'
    );

     public function getId()
    {
        return $this->getCol('id');
    }

    public function getType()
    {
        return $this->getCol('type');
    }

    public function getRole()
    {
        $type = $this->getCol('type');
        if (isset($this->roleMap[$type])) {
            return $this->roleMap[$type];
        }
    }

    public function getRoles()
    {
        $role = $this->getRole();
        if ($role) {
            return array($role);
        }
        return array();
    }

    public function getPassword()
    {
        return $this->getCol('pwd');
    }

    public function getSalt()
    {

    }

    public function getUsername()
    {
        return $this->getCol('username');
    }

    public function eraseCredentials()
    {

    }
}
