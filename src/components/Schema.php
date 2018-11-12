<?php

namespace ethercap\apiBase\components;

use yii\base\Component;
use yii\base\Model;
use ethercap\apiBase\validatorRenderers\Renderer;

class Schema extends Component
{
    /**
     * @param Model $model
     * @param string $field
     * @return array;
     */
    public static function buildField($model, $field)
    {
        $validators = $model->getActiveValidators($field);
        $ret = [];
        foreach ($validators as $validator) {
            $ret[] = self::toArray($validator, $model, $field);
        }
        return $ret;
    }

    /**
     * @param \yii\validators\Validator $validator
     * @param Model $model
     * @param $field
     * @return array
     */
    protected static function toArray($validator, $model, $field)
    {
        return Renderer::render($validator, $model, $field);
    }
}
