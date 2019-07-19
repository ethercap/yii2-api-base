<?php

namespace ethercap\apiBase\widgets;

use yii\base\InvalidConfigException;

class ListApi extends Widget
{
    public $dataProvider;

    public $serializerOptions = [
        'collectionEnvelope' => 'items',
        //'linksEnvelope' => 'links',
        'metaEnvelope' => 'meta',
        'sortEnvelope' => 'sort',
    ];

    /**
     * Initializes the Api.
     */
    public function init()
    {
        parent::init();
        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }
        $this->initSerializer();
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
