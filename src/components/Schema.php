<?php

namespace ethercap\apiBase\components;

use Yii;
use yii\base\Component;
use yii\base\Model;
use yii\validators\Validator;

class Schema extends Component
{
    /**
     * @param Model $model
     * @return array;
     */
    public static function build($model)
    {
        $fields = $model->activeAttributes();
        $schema = [];
        foreach ($fields as $field) {
            $schema[$field] = [
                'label' => $model->getAttributeLabel($field),
                'rules' => self::getValidators($model->getActiveValidators($field), $field),
            ];
        }
        return $schema;
    }

    /**
     * @param Model $model
     * @param string $field
     * @return array;
     */
    public static function buildField($model, $field)
    {
        return self::getValidators($model->getActiveValidators($field), $field);
    }

    /**
     * @param \yii\validators\Validator $validators
     * @param $field
     * @return array
     */
    protected static function getValidators($validators, $field)
    {
        $ret = [];
        foreach ($validators as $validator) {
            $ret[] = self::toArray($validator, $field);
        }
        return $ret;
    }

    /**
     * @param \yii\validators\Validator $validator
     * @param $field
     * @return array
     */
    protected static function toArray($validator, $field)
    {
        $ret = [];
        switch (get_class($validator)) {
            default:
                $ret = array_merge($ret, self::getConfigs($validator, $field));
                break;
        }
        $ret = ['name' => strtolower(
            str_replace('Validator', '',
                substr(get_class($validator),
                    strrpos(get_class($validator), '\\') + 1
                )
            )
        )] + $ret;

        return $ret;
    }

    protected static function getConfigs($validator, $field)
    {
        $reflect = new \ReflectionClass(Validator::class);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $filter = [];
        foreach ($props as $property) {
            if ($property->name == 'message') {
                continue;
            }
            $filter[$property->name] = 1;
        }
        $ret = array_diff_key(Yii::getObjectVars($validator), $filter);
        if (array_key_exists('message', $ret)) {
            $ret['message'] = self::formatMessage($ret['message'], $ret + ['attribute' => $field]);
        }
        return $ret;
    }

    protected static function formatMessage($message, $params)
    {
        if (Yii::$app !== null) {
            return \Yii::$app->getI18n()->format($message, $params, Yii::$app->language);
        }

        $placeholders = [];
        foreach ((array) $params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }

        return ($placeholders === []) ? $message : strtr($message, $placeholders);
    }
}
