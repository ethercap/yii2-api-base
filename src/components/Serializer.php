<?php

namespace ethercap\apiBase\components;

use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;
use lspbupt\common\helpers\SysMsg;
use yii\rest\Serializer as BaseSerializer;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Arrayable;
use yii\helpers\Inflector;
use yii\i18n\Formatter;

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

    public $formatter;

    public function init()
    {
        parent::init();
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
    }

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
        $result = $this->normalizeAttributes($model);
        if ($this->schemaEnvelope && $this->request->get('schema')) {
            return array_merge($result, $this->serializeSchema($model));
        } else {
            return $result;
        }
    }

    protected function serializeModelErrors($model)
    {
        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[$name] = SysMsg::get($message);
        }
        return $result;
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
        return [$this->schemaEnvelope => Schema::build($model)];
    }

    protected function normalizeAttributes($model)
    {
        if ($this->columns === null) {
            if ($model instanceof Model) {
                $this->columns = $model->attributes();
            } elseif (is_object($model)) {
                $this->columns = $model instanceof Arrayable ? array_keys($model->toArray()) : array_keys(get_object_vars($model));
            } elseif (is_array($model)) {
                $this->columns = array_keys($model);
            } else {
                throw new InvalidConfigException('The "model" property must be either an array or an object.');
            }
            sort($this->columns);
        }

        $ret = [];
        foreach ($this->columns as $i => $attribute) {
            if (is_string($attribute)) {
                if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
                    throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }
                $attribute = [
                    'attribute' => $matches[1],
                    'format' => isset($matches[3]) ? $matches[3] : 'raw',
                    'label' => isset($matches[5]) ? $matches[5] : null,
                ];
            }

            if (!is_array($attribute)) {
                throw new InvalidConfigException('The attribute configuration must be an array.');
            }

            if (!isset($attribute['format'])) {
                $attribute['format'] = 'raw';
            }
            if (isset($attribute['attribute'])) {
                $attributeName = $attribute['attribute'];
                if (!isset($attribute['label'])) {
                    $attribute['label'] = $model instanceof Model ? $model->getAttributeLabel($attributeName) : Inflector::camel2words($attributeName, true);
                }
                if (!array_key_exists('value', $attribute)) {
                    $attribute['value'] = ArrayHelper::getValue($model, $attributeName);
                }
            } elseif (!isset($attribute['label']) || !array_key_exists('value', $attribute)) {
                throw new InvalidConfigException('The attribute configuration requires the "attribute" element to determine the value and display label.');
            }

            if ($attribute['value'] instanceof \Closure) {
                $attribute['value'] = call_user_func($attribute['value'], $model, $this);
            }

            $key = ArrayHelper::remove($attribute, 'attribute');
            $format = ArrayHelper::remove($attribute, 'format');
            $attribute['value'] = $this->formatter->format($attribute['value'], $format);
            if (is_numeric($i)) {
                $ret[$key] = $attribute;
            } else {
                $ret[$i] = $attribute;
            }
        }
        return $ret;
    }
}
