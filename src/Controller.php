<?php

namespace ethercap\apiBase;

use yii\web\Controller as BaseController;

class Controller extends BaseController
{
    public function init()
    {
        $this->enableCsrfValidation = false;
        parent::init();
    }

    /**
     * Render View File Use ethercap\apiBase\components\responseTemplates\ITemplate
     *
     * @param $view
     * @param array $params
     * @return string
     */
    public function renderApi($view, $params = [])
    {
        return $this->getView()->renderApi($view, $params, $this);
    }

    /**
     * Render View File Use ResBuilder rtData only
     * @param $view
     * @param array $params
     * @return string
     */
    public function renderApiPartial($view, $params = [])
    {
        return $this->getView()->renderApiPartial($view, $params, $this);
    }

    /**
     * Returns the view object that can be used to render views or view files.
     * The [[render()]], [[renderPartial()]] and [[renderFile()]] methods will use
     * this view object to implement the actual view rendering.
     * If not set, it will default to the "view" application component.
     * @return \ethercap\apiBase\View|\yii\web\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        return parent::getView();
    }
}
