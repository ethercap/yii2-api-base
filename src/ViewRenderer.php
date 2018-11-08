<?php

namespace ethercap\apiBase;

use yii\di\Instance;
use ethercap\apiBase\components\ResBuilder;
use yii\base\ViewRenderer as BaseViewRenderer;

/**
 * Api ViewRenderer
 *
 * 将viewFile文件渲染成一个数组返回
 *
 * @package ethercap\apiBase
 */
class ViewRenderer extends BaseViewRenderer
{
    public $view;

    public $resBuilder = ResBuilder::class;

    protected $_resBuilder;

    public function init()
    {
        $this->_resBuilder = Instance::ensure($this->resBuilder, ResBuilder::class);
        parent::init();
    }

    /**
     * 渲染方法 将viewFile 渲染成
     *
     * @param View $view
     * @param string $viewFile 文件路径
     * @param array $params
     * @return array
     * @throws \Throwable
     */
    public function render($view, $viewFile, $params)
    {
        $this->view = $view;
        $res = clone $this->_resBuilder;
        return $this->renderApiFile($viewFile, $params, $res);
    }

    /**
     * @param    string   $_file_ 文件路径
     * @param array $_params_
     * @param ResBuilder $res
     * @return array
     * @throws \Throwable
     */
    protected function renderApiFile($_file_, $_params_ = [], $res = null)
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require $_file_;
            return $res->run();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}
