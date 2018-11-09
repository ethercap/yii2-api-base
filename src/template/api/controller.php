<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\helpers\StringHelper;
use yii\db\ActiveRecordInterface;


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
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * <?= $controllerClass ?>对于<?= $modelClass ?>的相关操作。
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete', 'bulk-delete'],
                        'allow' => true,
                        'roles' => ['rd'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 列出所有的<?= $modelClass ?>models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
            'sort' => [
                'defaultOrder' => [
                    //'id' => ['desc'],
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * 展示单个<?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title'=> "<?= $modelClass ?> #".<?= $actionParams ?>,
            'content'=>$this->renderAjax('view', [
                'model' => $this->findModel(<?= $actionParams ?>),
            ]),
            'footer'=> Html::button('关闭',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::a('编辑',['update','<?= substr($actionParams,1) ?>'=><?= $actionParams ?>],['class'=>'btn btn-primary','role'=>'modal-remote'])
        ];
    }

    /**
     * 创建一个新的<?= $modelClass ?> model.
     * 为了ajax使用，返回json
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new <?= $modelClass ?>();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->load($request->post()) && $model->save()) {
            return [
                'forceReload'=>'#crud-datatable-pjax',
                'title'=> "创建<?= $modelClass ?>",
                'content'=>'<span class="text-success">创建成功</span>',
                'footer'=> Html::button('关闭',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a('创建更多',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        }
        return [
            'title'=> "创建<?= $modelClass ?>",
            'content'=>$this->renderAjax('create', [
                'model' => $model,
            ]),
            'footer'=> Html::button('关闭',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::button('保存',['class'=>'btn btn-primary','type'=>"submit"])
        ];
    }

    /**
     * 修改<?= $modelClass ?> model.
     * ajax请求返回json数据
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $request = Yii::$app->request;
        $model = $this->findModel(<?= $actionParams ?>);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($model->load($request->post()) && $model->save()){
            return [
                'forceReload'=>'#crud-datatable-pjax',
                'title'=> "<?= $modelClass ?> :".<?= $actionParams ?>,
                'content'=>'<span class="text-success">修改成功</span>',
                'footer'=> Html::button('关闭',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::a('编辑',['update','<?= substr($actionParams,1) ?>'=><?= $actionParams ?>],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        }
        return [
            'title'=> "编辑<?= $modelClass ?> #".<?= $actionParams ?>,
            'content'=>$this->renderAjax('update', [
                'model' => $model,
            ]),
            'footer'=> Html::button('关闭',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::button('保存',['class'=>'btn btn-primary','type'=>"submit"])
        ];
    }

    /**
     * 删除一条<?= $modelClass ?> model记录。
     * 返回json的数据
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $request = Yii::$app->request;
        $this->findModel(<?= $actionParams ?>)->setDelete();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
    }

     /**
     * 批量删除<?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks'));
        $models = <?=$modelClass?>::findAll(['<?= $pks[0] ?>' => $pks]);
        foreach ( $models as $model ) {
            $model->setDelete();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
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
            throw new NotFoundHttpException('该页面不存在');
        }
    }
}
