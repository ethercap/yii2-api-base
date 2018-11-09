<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">
    <div class="panel-body">
        <?= "<?php " ?>$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'layout' => 'inline',
            'fieldConfig' => [
                'template' => '{beginWrapper}<div class="row"><div class="col-sm-3">{label}</div><div class="col-sm-9">{input}{error}{hint}</div></div>{endWrapper}',
                'labelOptions' => ['class' => ""],
                'options' => ['class' => 'form-group col-sm-6', "style"=>"padding-top:10px"],
            ],
        ]);

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    if (++$count < 6) {
        echo "        echo " . $generator->generateActiveSearchField($attribute) . ";\n";
    } else {
        echo "        // echo " . $generator->generateActiveSearchField($attribute) . ";\n";
    }
}
echo "        ?>\n";
?>
        <div class="form-group" style="padding-top:10px">
            <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('查询') ?>, ['class' => 'btn btn-primary text-center']) ?>
            <?= "<?= " ?>Html::resetButton(<?= $generator->generateString('清空') ?>, ['class' => 'btn btn-default text-center']) ?>
        </div>

        <?= "<?php " ?>ActiveForm::end(); ?>
    </div>
</div>
dasdasd
ads
ads
sad

