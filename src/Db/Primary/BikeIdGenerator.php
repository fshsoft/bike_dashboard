<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class BikeIdGenerator extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'stub' => 'a',
    );
}
