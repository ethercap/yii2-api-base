<?php

namespace ethercap\apiBase\components;

use yii\base\Component;

abstract class Column extends Component
{
    public $model;

    abstract public function evaluate();
}
