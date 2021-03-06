<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class Bike extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'elock_id' => 0,
        'user_id' => null,
        'status' => 1,
        'lat' => '',
        'lng' => '',
        'create_time' => null,
    );
}
