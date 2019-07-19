<?php

namespace ethercap\apiBase\exceptions;

use yii\base\Exception;

class TypeCastException extends Exception
{
    public $type;
    public $value;

    public function __construct(string $message = '', string $type = '', $value = null)
    {
        parent::__construct($message, 0, null);
        $this->type = $type;
        $this->value = $value;
        $this->message = $message;
    }

    public function getName()
    {
        return 'TypeCastException';
    }
}
