<?php

namespace ethercap\apiBase\components;

use yii\base\Component;

class Value extends Component
{
    public $builder;
    public $default;

    public function evaluate()
    {
        $ret = null;
        return isset($ret) ? $ret : $this->default;
    }
}
