<?php

namespace ethercap\apiBase\components\typeCast;

use ethercap\apiBase\exceptions\TypeCastException;
use yii\helpers\ArrayHelper;

class JsonTypeCast implements ITypeCast
{
    public static function cast($type, &$value)
    {
        switch ($type) {
            case 'int':
            case 'integer':
                if (is_int($value)) {
                    return;
                }
                $value = intval($value);
                break;
            case 'str':
            case 'string':
                if (is_string($value)) {
                    return;
                }
                $value = strval($value);
                break;
            case 'bool':
            case 'boolean':
                if (is_bool($value)) {
                    return;
                }
                $value = boolval($value);
                break;
            case 'array':
                if (is_scalar($value)) {
                    throw new TypeCastException('Cast scalar value to array!', $type, $value);
                }
                if (empty($value) || $value == (new \stdClass())) {
                    $value = new \stdClass();
                    return;
                }
                if (is_array($value) && !ArrayHelper::isAssociative($value, false)) {
                    return;
                }
                $value = array_values(ArrayHelper::toArray($value));
                break;
            case 'object':
                if (is_scalar($value)) {
                    throw new TypeCastException('Cast scalar value to object!', $type, $value);
                }
                if (empty($value)) {
                    $value = new \stdClass();
                    return;
                }
                break;
        }
    }
}
