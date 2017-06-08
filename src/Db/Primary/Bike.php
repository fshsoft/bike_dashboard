<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class Bike extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'elock_id' => null,
        'lat' => '',
        'lng' => '',
        'create_time' => null,
    );
}
