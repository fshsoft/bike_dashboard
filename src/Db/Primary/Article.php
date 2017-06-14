<?php

namespace Bike\Dashboard\Db\Primary;

use Bike\Dashboard\Db\AbstractEntity;

class Article extends AbstractEntity
{
    protected static $pk = 'id';

    protected static $cols = array(
        'id' => null,
        'category_id' => null,
        'title' => null,
        'sub_title' => null,
        'content' => null,
        'picpath' => null,
        'hits' => 0,
        'likes' => 0,
        'sort' => 0,
        'status' => 0,
        'create_time' => null,
        'last_time' => null,
    );
}
