<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();

echo "<?php\n";

?>
use yii\helpers\Url;
use yii\helpers\Html;
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
<?php
    $count = 0;
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "    [\n";
            echo "        'class'=>'\kartik\grid\DataColumn',\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "        'vAlign' => 'middle',\n";
            echo "        'hAlign' => 'center',\n";
            echo "    ],\n";
        } else {
            echo "    /* [\n";
            echo "        'class'=>'\kartik\grid\DataColumn',\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "        'vAlign' => 'middle',\n";
            echo "        'hAlign' => 'center',\n";
            echo "    ],*/\n";
        }
    }
?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'width' => '200px',
        'template' => '<div class="btn-group">{update}{view}{delete}</div>',
        'header' => "操作",
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return Html::a("查看", ["view", "id" => $model->id], [
                    'class' => 'btn bg-teal',
                    'role'=>'modal-remote',
                    'data-toggle'=>'tooltip'
                ]);
            },
            'update' => function ($url, $model, $key) {
                return Html::a("修改", ["update", "id" => $model->id], [
                    'class' => 'btn btn-info',
                    'role'=>'modal-remote',
                    'data-toggle'=>'tooltip'
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a("删除", ["delete", "id" => $model->id], [
                    'class' => 'btn bg-orange',
                    'role'=>'modal-remote',
                    'data-confirm'=>false,
                    'data-method'=>false,  // 关闭yii的默认请求
                    'data-toggle'=>'tooltip',
                    'data-confirm-ok' => '确定',
                    'data-confirm-cancel' => '取消',
                    'data-request-method'=>'post',
                    'data-confirm-title'=>'温馨提示',
                    'data-confirm-message'=>'你确定要删除该记录么？'
                ]);
            },
        ],
    ],
];
