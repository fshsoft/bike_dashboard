<?php

namespace Bike\Dashboard\Mongodb\Dao;

class Bike extends AbstractEntity
{

    protected static $cols = array(
        'id' => null,
        'status' => null,
        'lat' => '',
        'lng' => '',
    );
}
