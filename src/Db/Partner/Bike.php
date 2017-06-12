<?php

namespace Bike\Dashboard\Db\Partner;

use Bike\Dashboard\Db\AbstractEntity;

class Bike extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'elock_id' => null,
        'client_id' => null,
        'user_id' => null,
        'agent_id' => null,
        'static_revenue' => 0,
        'dynamic_revenue' => 0,
        'revenue' => 0,
        'status' => null,
        'lat' => '',
        'lng' => '',
        'create_time' => null,
    );
}
