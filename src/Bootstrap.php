<?php

namespace ethercap\apiBase;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::$classMap['yii\base\Model'] = '@ethercap/apiBase/components/Model.php';
    }
}
