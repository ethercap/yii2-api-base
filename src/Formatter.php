<?php

namespace ethercap\apiBase;

use yii\base\Behavior;

class Formatter extends Behavior
{
    public function asIntval($params)
    {
        return intval($params);
    }
}
