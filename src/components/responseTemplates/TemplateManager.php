<?php

namespace ethercap\apiBase\components\responseTemplates;

use yii\di\Container;

/**
 * Class TemplateManager
 * @package ethercap\apiBase\components\templates
 * @method getRes()
 */
class TemplateManager extends Container
{
    public $autoChoose;

    protected $current;

    public function use($name)
    {
        $this->current = $name;
        return $this;
    }

    public function getCurrentTplInstance()
    {
        if (!$this->current) {
            $this->autoChoose();
        }
        return $this->get($this->current);
    }

    public function autoChoose()
    {
        $this->current = call_user_func($this->autoChoose);
        return $this;
    }

    public function loadBuilder($obj)
    {
        $this->getCurrentTplInstance()->builder = $obj;
        return $this;
    }

    public function __call($name, $params)
    {
        if (method_exists($this->getCurrentTplInstance(), $name)) {
            return call_user_func_array([$this->getCurrentTplInstance(), $name], $params);
        }
        return parent::__call($name, $params);
    }
}
