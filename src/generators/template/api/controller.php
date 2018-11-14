<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
    use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
    use yii\data\ActiveDataProvider;
<?php endif; ?>
use yii\web\NotFoundHttpException;
use ethercap\apiBase\Controller;

class <?= $controllerClass ?> extends Controller
{
    public function behaviors()
    {
        return [
            'log' => ['class' => 'common\filters\LogFilter']
        ];
    }

    public function actionList()
    {
        Yii::$app->response->format = 'json';
    <?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_model_res.api', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    <?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);
        return $this->renderApi('index_model_res.api', [
            'dataProvider' => $dataProvider,
        ]);
    <?php endif; ?>
    }

    public function actionOriginList()
    {
        Yii::$app->response->format = 'json';
        <?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index_origin_res.api', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        <?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);
        return $this->renderApi('index_origin_res.api', [
            'dataProvider' => $dataProvider,
        ]);
        <?php endif; ?>
    }

    public function actionDetailView(<?= $actionParams ?>)
    {
        Yii::$app->response->format = 'json';
        $model = $this->findModel(<?= $actionParams ?>);
        return $this->renderApi('detail.api', ['model' => $model]);
    }

    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->renderApi('success.api', ['model' => $model]);
        }
        return $this->renderApi('detail.api', ['model' => $model]);
    }

    public function actionDelete(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        $model->setDelete();
        return $this->renderApi('success.api', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->renderApi('success.api', ['model' => $model]);
        }
        return $this->renderApi('detail.api', ['model' => $model]);
    }

    protected function findModel(<?= $actionParams ?>)
    {
        <?php
        $condition = [];
        foreach ($pks as $pk) {
            $condition[] = "'$pk' => \$$pk";
        }
        $condition = '[' . implode(', ', $condition) . ']';
        ?>
    if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在');
        }
    }
}
