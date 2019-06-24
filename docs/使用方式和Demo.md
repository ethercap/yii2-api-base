# 一、apiBase文档

1. [简介](简介.md)
2. [接口规范](接口规范.md)
2. [使用方式和Demo](使用方式和Demo.md)
2. [小部件](小部件.md)
2. [代码生成](代码生成.md)
2. [错误处理](错误处理.md)
2. [更新](更新.md)

## 配置(已通过bootstrap方法默认提供，不需要额外配置)

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

- view文件的后缀设置为.api，其他规则与view相同。
- 页面中默认注入ResBuilder对象 $res 用于构建返回
- $res 提供data和field两种方法，签名如下：

```
/**
 * 将结果包装在key下，如果value为null会返回一个Value对象用于调起Widget
 *
 * @param null  $key
 * @param null  $value
 * @param array $default
 * @return Value|null
 */
public function field($key = null, $value = null, $default = []);
   
/**
 * 如果value为null会返回一个Value对象用于调起Widget
 *
 * @param null  $value
 * @param array $default
 * @return Value|null
 */
public function data($value = null, $default = []);
```

##示例代码
- [Controller](src/demos/controllers/IndexController.php) 
- [views](src/demos/views) 

## Demo

提供了一个demo
- api-base-demo/list?withConfig=1
- api-base-demo/origin-list?withConfig=1
- api-base-demo/detail-view?withConfig=1