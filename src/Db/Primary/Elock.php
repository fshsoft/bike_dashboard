<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class Elock extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'mac' => '',
        'bike_id' => 0,
        'create_time' => null,
    );
}
