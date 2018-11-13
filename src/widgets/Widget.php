<?php

namespace ethercap\apiBase\widgets;

use Yii;
use yii\base\Widget as BaseWidget;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;

class Widget extends BaseWidget
{
    /**
     * @var \ethercap\apiBase\components\ResBuilder;
     */
    public $builder;

    public $useModelResponse;

    public function init()
    {
        parent::init();
        if ($this->builder === null) {
            throw new InvalidConfigException('The "builder" property must be set.');
        }
    }

    final public static function begin($config = [])
    {
        throw new InvalidCallException();
    }

    final public static function end()
    {
        throw new InvalidCallException();
    }

    /**
     * @param array $config
     * @return mixed
     * @throws \Exception
     */
    public static function widget($config = [])
    {
        ob_start();
        ob_implicit_flush(false);
        try {
            /* @var $widget Widget */
            $config['class'] = get_called_class();
            $widget = Yii::createObject($config);
            $out = '';
            if ($widget->beforeRun()) {
                $result = $widget->run();
                $out = $widget->afterRun($result);
            }
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        return $out;
    }

    public function renderApi($view, $params = [], $context = null)
    {
        return $this->getView()->render($view, $params, $this);
    }
}
