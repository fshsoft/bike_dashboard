<?php

namespace Bike\Dashboard\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

use Bike\Dashboard\Db\Primary\Admin;


class User extends Admin implements UserInterface
{
    public function getId()
    {
        return $this->getCol('id');
    }

    public function getRoles()
    {
        return ['ROLE_ADMIN'];
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

    public function getName()
    {
        return $this->getCol('name');
    }

    public function eraseCredentials()
    {

    }
}
