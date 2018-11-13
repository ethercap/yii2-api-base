<?php

namespace ethercap\apiBase\demos\controllers;

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
}
