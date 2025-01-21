<?php

namespace ethercap\apiBase\components;

use ethercap\apiBase\components\typeCast\JsonTypeCast;
use ethercap\common\helpers\SysMsg;
use Yii;
use yii\base\Arrayable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\Sort;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\i18n\Formatter;
use yii\rest\Serializer as BaseSerializer;
use yii\web\Link;

class Serializer extends BaseSerializer
{
    public $useModelResponse;

    public $linksEnvelope = false;

    /**
     * 配置这个属性后会返回全部可用排序信息，依赖collectionEnvelope，如果collectionEnvelope不配置则不会返回附加信息。
     */
    public $sortEnvelope;
    /**
     * 配置这个属性后会返回model的配置信息，（不依赖collectionEnvelope，schema 并不是列表属性，
     * 而是detail的属性，准确的说只有一个model或者form才有schema）。
     */
    public $addConfigParam = 'withConfig';

    public $addConfig;

    public $columns;

    public $errInstance;

    public $formatter;

    public $typeCastClass = JsonTypeCast::class;

    protected $canAddConfig = true;

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

    public function serializeModels(array $models)
    {
        foreach ($models as $i => $model) {
            if ($row = $this->serializeModel($model)) {
                $models[$i] = $row;
            }
        }
        return $models;
    }

    public function serializeModel($model)
    {
        return $this->normalizeAttributes($model);
    }

    protected function serializeModelErrors($model)
    {
        $this->errInstance = $model;
        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[$name] = SysMsg::get($message);
        }
        $ret = ['errors' => $result];
        return $ret;
    }

    protected function serializeDataProvider($dataProvider)
    {
        $this->canAddConfig = false;
        $result = parent::serializeDataProvider($dataProvider);
        if (is_array($result) && array_key_exists($this->collectionEnvelope, $result)) {
            if ($this->sortEnvelope && ($sorter = $dataProvider->getSort()) !== false) {
                return array_merge($result, $this->serializeSorter($sorter));
            }
        }
        return $result;
    }

    protected function serializeSorter(Sort $sorter)
    {
        return [$this->sortEnvelope => $sorter->getAttributeOrders()];
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
        return $this->normalizeResponse($model);
    }

    protected function normalizeResponse($model)
    {
        $ret = [];
        foreach ($this->columns as $i => $attribute) {
            if ($attribute instanceof \Closure) {
                $attribute = ['value' => $attribute];
            }
            if (is_string($attribute)) {
                if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?(:(\w*))?$/', $attribute, $matches)) {
                    throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }
                $attribute = [
                    'attribute' => $matches[1],
                    'format' => isset($matches[3]) ? $matches[3] : 'raw',
                    'label' => isset($matches[5]) ? $matches[5] : null,
                    'rules' => isset($matches[7]) ? (array) $matches[7] : null,
                ];
            }

            if (!is_array($attribute)) {
                throw new InvalidConfigException('The attribute configuration must be an array.');
            }

            if (isset($attribute['class'])) {
                $class = ArrayHelper::remove($attribute, 'class');
                $column = new $class();
                $column->model = $model;
                if (is_subclass_of($column, Column::class)) {
                    foreach ($attribute as $key => $value) {
                        if ($column->canSetProperty($key)) {
                            $column->{$key} = $value;
                            unset($attribute[$key]);
                        }
                    }
                    $attribute['value'] = $column->evaluate();
                }
            }

            if (!isset($attribute['format'])) {
                $attribute['format'] = 'raw';
            }

            if ($this->useModelResponse) {
                if (!isset($attribute['rules']) || !$this->canAddConfig) {
                    unset($attribute['rules']);
                }

                if (!isset($attribute['label']) || !$this->canAddConfig) {
                    unset($attribute['label']);
                }
            }

            if (isset($attribute['attribute'])) {
                $attributeName = $attribute['attribute'];
                if ($this->useModelResponse) {
                    if (!isset($attribute['label']) && $this->addConfig()) {
                        $attribute['label'] = $model instanceof Model ? $model->getAttributeLabel($attributeName) : Inflector::camel2words($attributeName, true);
                    }

                    if (!isset($attribute['rules']) && $this->addConfig()) {
                        $attribute['rules'] = Schema::buildField($model, $attribute['attribute']);
                    }
                }
                if (!array_key_exists('value', $attribute)) {
                    $attribute['value'] = ArrayHelper::getValue($model, $attributeName);
                }
            } elseif (!array_key_exists('value', $attribute)) {
                throw new InvalidConfigException('The attribute configuration requires the "attribute" element to determine the value.');
            }

            if ($attribute['value'] instanceof \Closure) {
                $attribute['value'] = call_user_func($attribute['value'], $model, $this);
            }

            $key = ArrayHelper::remove($attribute, 'attribute');

            $format = ArrayHelper::remove($attribute, 'format');
            $this->formatter->nullDisplay = null;
            $attribute['value'] = $this->formatter->format($attribute['value'], $format);

            $type = ArrayHelper::remove($attribute, 'type');
            if (is_string($type)) {
                $this->typeCastClass::cast($type, $attribute['value']);
            }

            if (is_numeric($i) && $key) {
                $ret[$key] = $this->useModelResponse ? $attribute : $attribute['value'];
            } else {
                $ret[$i] = $this->useModelResponse ? $attribute : $attribute['value'];
            }
        }
        return $ret;
    }

    protected function addConfig()
    {
        $paramConfig = Yii::$app->request->isConsoleRequest ? false : Yii::$app->request->get($this->addConfigParam);
        return ($this->addConfigParam && $paramConfig && $this->canAddConfig) || ($this->addConfig && $this->canAddConfig);
    }

    protected function serializePagination($pagination)
    {
        $ret = [];
        if ($this->linksEnvelope) {
            $ret[$this->linksEnvelope] = Link::serialize($pagination->getLinks(true));
        }

        if ($this->metaEnvelope) {
            $ret[$this->metaEnvelope] = [
                'currentPage' => $pagination->getPage() + 1,
                'pageCount' => $pagination->getPageCount(),
                'perPage' => $pagination->getPageSize(),
                'totalCount' => $pagination->totalCount,
            ];
        }

        return $ret;
    }
}
