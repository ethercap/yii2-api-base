<?php

namespace ethercap\apiBase\components\responseTemplates;

use yii\base\BaseObject;

/**
 * 当发生错误时，不会修改code，而是将错包裹在errors内返回。
 *
 * Class FaasTemplate
 * @package ethercap\apiBase\components\responseTemplates
 * @property \ethercap\apiBase\components\ResBuilder $builder
 */
class NoBlockTpl extends BaseObject implements ITemplate
{
    public $builder;

    public $template = [
        'code' => '{code}',
        'message' => '{message}',
        'data' => '{data}',
    ];

    public function getRes()
    {
        $ret = $this->template;
        array_walk_recursive($ret, function (&$v) {
            $matches = [];
            if (preg_match('#^\{(.*)\}$#', $v, $matches)) {
                $v = $this->{'get' . ucfirst($matches[1])}();
            }
        });
        return $ret;
    }

    protected function getCode()
    {
        return 0;
    }

    protected function getMessage()
    {
        return '操作成功';
    }

    protected function getData()
    {
        return $this->builder->rtData;
    }
}
