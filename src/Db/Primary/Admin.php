<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class Admin extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'username' => null,
        'name' => null,
        'pwd' => null,
        'last_login_ip' => '',
        'last_login_time' => 0,
        'create_time' => null,
    );
}
