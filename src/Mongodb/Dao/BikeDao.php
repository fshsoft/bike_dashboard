<?php

namespace Bike\Dashboard\Mongodb\Dao;

class Bike extends AbstractDao
{

    protected static $cols = array(
        'id' => null,
        'status' => null,
        'lat' => '',
        'lng' => '',
    );
}
