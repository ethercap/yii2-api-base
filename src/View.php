<?php

namespace ethercap\apiBase;

use yii\web\View as BaseView;

class View extends BaseView
{
    public function renderApi($view, $params = [], $context = null)
    {
        $viewFile = $this->findViewFile($view, $context);
        return $this->renderFile($viewFile, $params, $context);
    }
}
