<?php

namespace Bike\Dashboard\Db\Dashboard;

use Bike\Dashboard\Db\AbstractEntity;

class Admin extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'name' => null,
    );
}
