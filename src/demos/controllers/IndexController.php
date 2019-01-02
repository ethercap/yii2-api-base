<?php

namespace ethercap\apiBase\demos\controllers;

use ethercap\apiBase\Serializer;
use lspbupt\common\helpers\SysMsg;
use Yii;
use backend\models\ProjectSearch;
use ethercap\apiBase\demos\models\Project;
use ethercap\apiBase\Controller;

class IndexController extends Controller
{
    public function actionList()
    {
        Yii::$app->response->format = 'json';
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderApi('@ethercap/apiBase/demos/views/index_model_res.api', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOriginList()
    {
        Yii::$app->response->format = 'json';
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderApi('@ethercap/apiBase/demos/views/index_origin_res.api', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetailView()
    {
        Yii::$app->response->format = 'json';
        $model = Project::findOne(245);
        return $this->renderApi('@ethercap/apiBase/demos/views/detail.api', [
            'model' => $model,
        ]);
    }

    public function actionSerializer()
    {
        $this->layout = false;
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('@ethercap/apiBase/demos/views/serializer', [
            'form' => (new Serializer())->useModelResponse()->setAddConfig()->setColumn([
                'scaleLower',
                'scaleUpper',
                'vendorId',
            ])->serialize($searchModel),
            'dataProvider' => (new Serializer())->useModelResponse()->setColumn([
                'scaleLower',
                'scaleUpper',
                'vendorId',
            ])->serialize($dataProvider),
        ]);
    }

    public function actionErrDetailView()
    {
        Yii::$app->response->format = 'json';
        $model = Project::findOne(245);
        $model->addError('tittle', 'C_ERR_DEMO');
        $model->addError('id', 'C_ERR_DEMO');
        return $this->renderApi('@ethercap/apiBase/demos/views/detail.api', [
            'model' => $model,
        ]);
    }

    public function actionErrList()
    {
        Yii::$app->response->format = 'json';
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //validate 会调用clearErrors 所以要在search之后添加错误。
        $searchModel->addError('id', 'C_ERR_DEMO');
        $searchModel->addError('title', 'C_ERR_DEMO');
        return $this->renderApi('@ethercap/apiBase/demos/views/index_model_res.api', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
SysMsg::register('C_ERR_DEMO', '示例错误');
