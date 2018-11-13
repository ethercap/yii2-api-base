<?php

namespace ethercap\apiBase;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::$classMap['yii\base\Model'] = '@ethercap/apiBase/components/Model.php';
        spl_autoload_unregister(['Yii', 'autoload']);
        spl_autoload_register(['Yii', 'autoload']);
    }

    public static function map()
    {
        return ['yii\base\Model' => '@ethercap/apiBase/components/Model.php'];
    }
}
