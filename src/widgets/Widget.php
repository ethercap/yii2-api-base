<?php

namespace ethercap\apiBase\widgets;

use Yii;
use yii\base\Widget as BaseWidget;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use ethercap\apiBase\components\Serializer;
use ethercap\apiBase\components\typeCast\JsonTypeCast;

class Widget extends BaseWidget
{
    /**
     * @var mixed 用于连接父子组件的上下文，参见 ModelsApi
     */
    public $context;

    /**
     * @var \ethercap\apiBase\components\ResBuilder;
     */
    public $builder;

    public $resTpl;

    public $serializer = Serializer::class;

    /**
     * @var bool serializer属性
     */
    public $useModelResponse;

    /**
     * @var bool serializer属性，使用对象形式返回接口信息时，是否将配置信息一并返回
     */
    public $addConfig;

    /**
     * @var string 指定参数名，决定是否返回配置信息，为空则仅由addConfig决定是否返回接口配置信息
     */
    public $addConfigParam = 'withConfig';

    /**
     * @var bool serializer属性
     */
    public $serializerOptions = [];

    /**
     * @var bool serializer属性
     */
    public $columns;

    /**
     * @var bool serializer属性
     */
    public $typeCastClass = JsonTypeCast::class;

    /**
     * @var Serializer
     */
    protected $_serializer;

    public function init()
    {
        parent::init();
        if ($this->builder === null) {
            throw new InvalidConfigException('The "builder" property must be set.');
        }
        if ($this->resTpl) {
            $this->builder->use($this->resTpl);
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

    public function initSerializer()
    {
        $this->_serializer = new $this->serializer(
            $this->serializerOptions +
            [
                'columns' => $this->columns,
                'useModelResponse' => $this->useModelResponse,
                'addConfig' => $this->addConfig,
                'addConfigParam' => $this->addConfigParam,
            ]
        );
    }
}
