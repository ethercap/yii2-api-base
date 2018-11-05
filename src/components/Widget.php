<?php

namespace ethercap\apiBase\components;

use yii\base\Widget as YiiWidget;
use yii\base\InvalidCallException;

class Widget extends YiiWidget
{
    final public static function begin($config = [])
    {
        throw new InvalidCallException();
    }

    final public static function end()
    {
        throw new InvalidCallException();
    }
}
