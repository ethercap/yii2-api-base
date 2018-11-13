<?php

namespace ethercap\apiBase\validatorRenderers;

use yii\base\Component;
use yii\captcha\CaptchaValidator;
use yii\validators\EachValidator;
use yii\validators\ExistValidator;
use yii\validators\Validator;
use yii\validators\DefaultValueValidator;
use yii\validators\FileValidator;
use yii\validators\FilterValidator;
use yii\validators\ImageValidator;
use yii\validators\InlineValidator;
use yii\validators\SafeValidator;
use yii\validators\UniqueValidator;

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
        return [
            'type' => self::getType($validator),
            'options' => $validator->getClientOptions($model, $field),
        ];
    }

    /**
     * @param $validator
     * @return string
     */
    public static function getType($validator): string
    {
        if ($type = array_search(get_class($validator), Validator::$builtInValidators)) {
            return $type;
        }
        if ($validator) {
            $type = strtolower(
            str_replace('Validator', '',
                substr(get_class($validator),
                    strrpos(get_class($validator), '\\') + 1
                )
            )
        );
        }
        return $type;
    }
}
