<?php

namespace ethercap\apiBase\widgets;

use yii\base\InvalidConfigException;

class DetailApi extends Widget
{
    public $model;

    /**
     * Initializes the Api.
     */
    public function init()
    {
        parent::init();
        if ($this->model === null) {
            throw new InvalidConfigException('The "model" property must be set.');
        }
        $this->initSerializer();
    }

    public function run()
    {
        $rtData = $this->_serializer->serialize($this->model);
        if ($errModel = $this->_serializer->errInstance) {
            $this->builder->pushError($errModel);
        }
        return $rtData;
    }
}
