<?php

namespace ethercap\apiBase\widgets;

use yii\base\InvalidConfigException;
use ethercap\apiBase\components\Serializer;

class DetailApi extends Widget
{
    public $model;

    public $serializer = Serializer::class;

    public $serializerOptions = [
    ];

    /**
     * @var Serializer
     */
    private $_serializer;

    public $columns;

    /**
     * Initializes the Api.
     */
    public function init()
    {
        parent::init();
        if ($this->model === null) {
            throw new InvalidConfigException('The "model" property must be set.');
        }
        $this->_serializer = new $this->serializer($this->serializerOptions
            + ['columns' => $this->columns] + ['useModelResponse' => $this->useModelResponse]);
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
