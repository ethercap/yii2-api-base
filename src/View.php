<?php

namespace ethercap\apiBase;

use yii\web\View as BaseView;

class View extends BaseView
{
    public function renderApi($view, $params = [], $context = null)
    {
        $originalExtension = $this->defaultExtension;
        $this->defaultExtension = 'api';
        $viewFile = $this->findViewFile($view, $context);
        $this->defaultExtension = $originalExtension;
        return $this->renderFile($viewFile, $params, $context);
    }

    public function renderApiPartial($view, $params = [], $context = null)
    {
        $originalExtension = $this->defaultExtension;
        $this->defaultExtension = 'api';
        $viewFile = $this->findViewFile($view, $context);
        $this->defaultExtension = $originalExtension;
        return $this->renderFile($viewFile, $params + ['_renderApiPartial' => true], $context);
    }
}
