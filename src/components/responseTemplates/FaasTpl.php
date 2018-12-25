<?php

namespace ethercap\apiBase\components\responseTemplates;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * 为兼容faas现有逻辑而存在的，不符合最新规范，会被取代掉
 *
 * Class FaasTemplate
 * @package ethercap\apiBase\components\responseTemplates
 * @property \ethercap\apiBase\components\ResBuilder $builder
 */
class FaasTpl extends BaseObject implements ITemplate
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
        if ($this->builder->hasError()) {
            return 2;
        }
        return 0;
    }

    protected function getMessage()
    {
        if ($this->builder->hasError()) {
            return '操作失败';
        }
        return '操作成功';
    }

    protected function getData()
    {
        return ArrayHelper::getValue($this->builder->rtData, 'errors', ArrayHelper::getValue($this->builder->rtData, 'params.errors'));
    }
}
