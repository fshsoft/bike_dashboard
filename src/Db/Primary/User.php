<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class User extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'mobile' => null,
        'pwd' => '',
        'name' => '',
        'id_no' => '',
        'is_certificated' => 0,
        'balance' => '0.00',
        'avatar' => '',
        'last_login_ip' => '',
        'last_login_time' => 0,
        'create_time' => null,
    );
}
