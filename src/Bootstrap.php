<?php

namespace ethercap\apiBase;

use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if (ArrayHelper::getValue($app->getComponents(), 'view.class') === View::class) {
            $app->set('view', [
                'class' => 'ethercap\apiBase\View',
                'renderers' => [
                    'api' => [
                        'class' => 'ethercap\apiBase\ViewRenderer',
                    ],
                ],
            ]);
        }
        if ($app->hasModule('gii')) {
            $app->getModule('gii')->generators = array_merge($app->getModule('gii')->generators, [
                'api' => [
                    'class' => 'ethercap\apiBase\generators\Generator',
                    'templates' => [
                        'default' => '@ethercap/apiBase/generators/template/api',
                    ],
                ],
            ]);
        }
    }
}
