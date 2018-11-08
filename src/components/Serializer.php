<?php

namespace ethercap\apiBase\components;

use yii\data\Sort;
use yii\helpers\ArrayHelper;
use yii\rest\Serializer as BaseSerializer;

class Serializer extends BaseSerializer
{
    /**
     * 配置这个属性后会返回全部可用排序信息，依赖collectionEnvelope，如果collectionEnvelope不配置则不会返回附加信息。
     */
    public $sortEnvelope;
    /**
     * 配置这个属性后会返回全部可用排序信息，（不依赖collectionEnvelope，schema 并不是列表属性，
     * 而是detail的属性，准确的说只有一个model或者form才有schema）。
     */
    public $schemaEnvelope;

    public $schemaParam = 'schema';

    public $columns = [];

    public $errInstance;

    protected function serializeModels(array $models)
    {
        foreach ($models as $i => $model) {
            if ($row = $this->serializeModel($model)) {
                $models[$i] = $row;
            }
        }
        return $models;
    }

    protected function serializeModel($model)
    {
        if (is_array($model)) {
            return array_intersect_key($model, array_combine($this->columns, $this->columns));
        } elseif (is_object($model)) {
            $result = ArrayHelper::toArray($model, [get_class($model) => $this->columns], false);
            if ($this->schemaEnvelope && $this->request->get('schema')) {
                return array_merge($result, $this->serializeSchema($model));
            } else {
                return $result;
            }
        } else {
            return $model;
        }
    }

    protected function serializeModelErrors($model)
    {
        $this->errInstance = $model;
        return [];
    }

    protected function serializeDataProvider($dataProvider)
    {
        $result = parent::serializeDataProvider($dataProvider);
        if (is_array($result) && array_key_exists($this->collectionEnvelope, $result)) {
            if ($this->sortEnvelope && ($sorter = $dataProvider->getSort()) !== false) {
                return array_merge($result, $this->serializeSorter($sorter));
            } else {
                return $result;
            }
        }
    }

    protected function serializeSorter(Sort $sorter)
    {
        $attributes = array_keys($sorter->attributes);
        $ret = null;
        foreach ($attributes as $attribute) {
            $ret[$attribute] = $sorter->createUrl($attribute);
        }
        return [$this->sortEnvelope => $ret];
    }

    protected function serializeSchema($model)
    {
        return [$this->schemaEnvelope => []];
    }
}
