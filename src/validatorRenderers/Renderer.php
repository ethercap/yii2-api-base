<?php

namespace ethercap\apiBase\validatorRenderers;

use yii\base\Component;
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
        $type = self::getType($validator);
        return [
            'type' => $type,
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
