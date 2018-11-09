<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
echo "<?php\n";
?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php "?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>
    <div id="ajaxCrudDatatable">
        <?="<?="?>GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                [
                    'content' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                        'role'=>'modal-remote',
                        'title'=> '创建新<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>',
                        'class'=>'btn btn-default'
                    ]).Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], [
                        'data-pjax'=>1,
                        'class'=>'btn btn-default',
                        'title'=>'刷新'
                    ]),
                ],
            ],
            'condensed' => true,
            'hover' => true,
            'panel' => [
                'type' => 'info',
                'heading' => '<i class="glyphicon glyphicon-list"></i> <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>列表',
                'after'=> '<span class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;'.Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp;删除所有选中', ["bulk-delete"] , [
                        "class"=>"btn btn-danger btn-xs",
                        'role'=>'modal-remote-bulk',
                        'data-confirm'=>false,
                        'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-confirm-title'=>'温馨提示',
                        'data-confirm-message'=>'你确定要删除该记录么？',
                        'data-confirm-ok' => '确定',
                        'data-confirm-cancel' => '取消',
                ]).'<div class="clearfix"></div>',
            ]
        ])<?="?>\n"?>
    </div>
</div>



