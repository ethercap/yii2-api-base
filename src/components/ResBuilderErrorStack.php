<?php

namespace ethercap\apiBase\components;

use yii\base\Component;
use yii\base\Model;

/**
 * Class ResBuilderErrorStack
 * @package ethercap\apiBase\components
 *
 * @property string $creator
 * @property Model $errorModel
 * @property Model[] $errors
 */
class ResBuilderErrorStack extends Component
{
    private $_creator;

    private $_stack = [];

    private static $_stacks = [];

    private static $_instance;

    public static function getInstance(string $creator)
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->_creator = $creator;
        }
        return self::$_instance;
    }

    public function getCreator()
    {
        return $this->_creator;
    }

    public function pushError($errorModels)
    {
        if (!is_array($errorModels)) {
            $errorModels = [$errorModels];
        }
        return array_push($this->_stack, ...$errorModels);
    }

    public function getErrorModel()
    {
        return reset($this->_stack);
    }

    public function getErrors()
    {
        return $this->_stack;
    }

    public function hasError()
    {
        return (bool) count($this->_stack);
    }

    /**
     * 释放当前builder的error信息
     */
    public function clear()
    {
        self::$_stacks[$this->_creator] = $this->_stack;
        self::$_instance = null;
    }
}
