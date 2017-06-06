<?php

namespace Bike\Dashboard\Db\Dashboard;

use Bike\Dashboard\Db\AbstractEntity;

class Passport extends AbstractEntity
{
    const TYPE_ADMIN = 1;
    const TYPE_CS_STAFF = 2;
    const TYPE_AGENT = 3;
    const TYPE_CLIENT = 4;

    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'username' => null,
        'pwd' => null,
        'role' => null,
        'last_login_ip' => null,
        'last_login_time' => null,
        'create_time' => null,
    );
}
