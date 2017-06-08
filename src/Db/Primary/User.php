<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class User extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'mobile' => null,
        'pwd' => null,
        'last_login_ip' => null,
        'last_login_time' => null,
        'create_time' => null,
    );
}
