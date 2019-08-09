<?php

namespace ethercap\apiBase\components\responseTemplates;

use ethercap\apiBase\components\ResBuilderErrorStack;
use yii\base\BaseObject;
use ethercap\common\helpers\SysMsg;

/**
 * 当发生错误时会按照SysMsg的方式返回错误。
 *
 * Class BlockTpl
 * @package ethercap\apiBase\components\responseTemplates
 * @property \ethercap\apiBase\components\ResBuilder $builder
 */
class BlockTpl extends BaseObject implements ITemplate
{
    public $builder;

    public $template = [
        'code' => '{code}',
        'message' => '{message}',
        'data' => '{data}',
    ];

    public function getRes()
    {
        if ($this->builder->hasError() && ResBuilderErrorStack::getInstance($this->builder->id)->creator == $this->builder->id) {
            $errorModel = $this->builder->getErrModel();
            ResBuilderErrorStack::getInstance($this->builder->id)->clear();
            return SysMsg::getErrData($errorModel);
        }

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
