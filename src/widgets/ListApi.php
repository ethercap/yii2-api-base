<?php

namespace ethercap\apiBase\widgets;

use yii\base\InvalidConfigException;
use ethercap\apiBase\components\Serializer;

class ListApi extends Widget
{
    public $dataProvider;

    public $serializer = Serializer::class;

    public $serializerOptions = [
        'collectionEnvelope' => 'items',
        'linksEnvelope' => 'links',
        'metaEnvelope' => 'meta',
        'sortEnvelope' => 'sort',
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
        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }
        $this->_serializer = new $this->serializer($this->serializerOptions
            + ['columns' => $this->columns] + ['useModelResponse' => $this->useModelResponse]);
    }

    public function run()
    {
        $rtData = $this->_serializer->serialize($this->dataProvider);
        if ($errModel = $this->_serializer->errInstance) {
            $this->builder->pushError($errModel);
        }
        return $rtData;
    }
}
