<?php

namespace ethercap\apiBase\widgets;

use yii\base\InvalidConfigException;

class ModelsApi extends ListApi
{
    /**
     * @ Model[] | callable with function($model)
     */
    public $models;

    /**
     * Initializes the Api.
     */
    public function init()
    {
        if ($this->models === null) {
            throw new InvalidConfigException('The "models" property must be set.');
        }
        $this->initSerializer();
    }

    public function run()
    {
        if (is_callable($this->models)) {
            $this->models = call_user_func($this->models, $this->context);
        }

        $rtData = $this->_serializer->serializeModels($this->models);
        if ($errModel = $this->_serializer->errInstance) {
            $this->builder->pushError($errModel);
        }
        return $rtData;
    }
}
