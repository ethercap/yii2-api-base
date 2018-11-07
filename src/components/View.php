<?php

namespace ethercap\apiBase\components;

use yii\web\View as YiiView;

class View extends YiiView
{
    public function renderApi($view, $params = [], $context = null)
    {
        $viewFile = $this->findViewFile($view, $context);
        return $this->renderFile($viewFile, $params, $context);
    }
}
