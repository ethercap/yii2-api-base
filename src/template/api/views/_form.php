<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'layout' => 'horizontal',
    ]);

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "    echo " . $generator->generateActiveField($attribute) . ";\n";
    }
}
    echo '    if (!Yii::$app->request->isAjax){ ?>'."\n"?>
	  	<div class="form-group">
	        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('创建') ?> : <?= $generator->generateString('编辑') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?="<?php } ?>\n"?>
    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
