<?php

namespace ethercap\apiBase\validatorRenderers;

use yii\base\Component;
use yii\captcha\CaptchaValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\EachValidator;
use yii\validators\ExistValidator;
use yii\validators\FileValidator;
use yii\validators\FilterValidator;
use yii\validators\ImageValidator;
use yii\validators\InlineValidator;
use yii\validators\RangeValidator;
use yii\validators\SafeValidator;
use yii\validators\UniqueValidator;
use yii\validators\Validator;

class Renderer extends Component
{
    /**
     * @param Validator $validator
     * @param           $model
     * @param           $field
     * @return array
     */
    public static function render($validator, $model, $field)
    {
        if ($validator instanceof InlineValidator
            || $validator instanceof FilterValidator
            || $validator instanceof FileValidator
            || $validator instanceof ImageValidator
            || $validator instanceof SafeValidator
            || $validator instanceof UniqueValidator
            || $validator instanceof DefaultValueValidator
            || $validator instanceof EachValidator
            || $validator instanceof ExistValidator
            || $validator instanceof CaptchaValidator
        ) {
            return [];
        }
        $res = [
            'type' => self::getType($validator),
            'options' => $validator->getClientOptions($model, $field),
        ];
        if ($validator instanceof RangeValidator) {
            if (method_exists($model, 'dict') && $dict = $model->dict($field)) {
                $res['dict'] = $dict;
            } else {
                $res['dict'] = array_combine($res['options']['range'], $res['options']['range']);
            }
        }
        return $res;
    }

    /**
     * @param $validator
     * @return string
     */
    public static function getType($validator)
    {
        foreach (Validator::$builtInValidators as $type => $class) {
            //这个方法严格限制必须是子类才会返回true，所以需要加上下边的array_search
            if (is_subclass_of($validator, $class)) {
                return $type;
            }
        }
        if ($type = array_search(get_class($validator), Validator::$builtInValidators)) {
            return $type;
        }
        if ($validator) {
            $type = strtolower(
                str_replace(
                    'Validator',
                    '',
                    substr(get_class($validator), strrpos(get_class($validator), '\\') + 1)
                )
            );
        }
        return $type;
    }
}
