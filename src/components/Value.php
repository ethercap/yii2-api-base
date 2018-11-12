<?php

namespace ethercap\apiBase\components;

use ethercap\apiBase\widgets\FormApi;
use Yii;
use yii\base\Component;
use ethercap\apiBase\widgets\Widget;
use ethercap\apiBase\widgets\DetailApi;
use ethercap\apiBase\widgets\ListApi;

class Value extends Component
{
    public $builder;
    public $default;

    public $detailWidget = DetailApi::class;
    public $listWidget = ListApi::class;
    public $formWidget = FormApi::class;

    /**
     * @var Widget
     */
    private $ret;

    public function evaluate()
    {
        if (isset($this->ret) && $this->ret instanceof Widget) {
            return $this->ret->run();
        } elseif (isset($this->ret)) {
            return $this->ret;
        } else {
            return $this->default;
        }
    }

    public function default($value)
    {
        $this->default = $value;
        return $this;
    }

    /**
     * @param  $class
     * @param $config
     * @return mixed
     * @throws
     */
    public function widget($class, $config)
    {
        $this->ret = Yii::createObject(['class' => $class, 'builder' => $this->builder] + $config);
        return $this;
    }

    public function list($config)
    {
        $this->ret = Yii::createObject(['class' => $this->listWidget, 'builder' => $this->builder] + $config);
        return $this;
    }

    public function detail($config)
    {
        $this->ret = Yii::createObject(['class' => $this->detailWidget, 'builder' => $this->builder] + $config);
        return $this;
    }

    public function form($config)
    {
        $this->ret = Yii::createObject(['class' => $this->formWidget, 'builder' => $this->builder] + $config);
        return $this;
    }
}
