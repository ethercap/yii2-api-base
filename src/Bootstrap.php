<?php

namespace ethercap\apiBase;

use Yii;
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\base\BootstrapInterface;
use ethercap\apiBase\demos\controllers\IndexController;

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
                    'class' => 'ethercap\apiBase\components\Generator',
                    'templates' => [
                        'default' => '@ethercap/apiBase/src/template/api',
                    ],
                ],
            ]);
        }
        if (YII_ENV == 'dev') {
            $app->controllerMap['api-base-demo'] = IndexController::class;
        }
    }
}
