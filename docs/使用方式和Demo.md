# 一、apiBase文档

1. [简介](简介.md)
2. [接口规范](接口规范.md)
2. [使用方式和Demo](使用方式和Demo.md)
2. [小部件](小部件.md)
2. [代码生成](小部件.md)

## 配置

```
    public function bootstrap($app)
    {
        if (ArrayHelper::getValue($app->getComponents(), 'view.class') === View::class) {
            $app->set('view', [
                'class' => 'ethercap\apiBase\View',
                'renderers' => [
                    'api' => [
                        'class' => 'ethercap\apiBase\ViewRenderer'
                    ],
                ]
            ]);
        }
        if ($app->hasModule('gii')) {
            $app->getModule('gii')->generators = array_merge($app->getModule('gii')->generators, [
                'api' => [
                    'class' => 'yii\gii\generators\crud\Generator',
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
```

## 使用
view文件的后缀设置为.api，其他规则与view相同。

## Demo

提供了一个配置后直接可用demo（在platform工程中，如他工程需要简单修改）
api-base-demo/list?withConfig=1
api-base-demo/origin-list?withConfig=1
api-base-demo/detail-view?withConfig=1