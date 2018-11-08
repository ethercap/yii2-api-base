<?php

namespace ethercap\apiBase\components;

use yii\base\Component;

class ResBuilder extends Component
{
    protected $_code;
    protected $_message;
    protected $_data;
    protected $_fields = [];

    protected $errors = [];

    public $rtData;

    public $template = [
        'code' => '{code}',
        'message' => '{message}',
        'data' => '{data}',
    ];

    public function run()
    {
        $res = $this->template;
        array_walk_recursive($res, function (&$v) {
            $matches = [];
            if (preg_match('#^\{(.*)\}$#', $v, $matches)) {
                $v = $this->{'get' . ucfirst($matches[1])}();
            }
        });
        return $res;
    }

    /**
     * @param null  $value
     * @param array $default
     * @return Value|null
     */
    public function data($value = null, $default = [])
    {
        if ($value !== null) {
            return $this->_data = $value;
        } else {
            return $this->_data = new Value(['default' => $default, 'builder' => $this]);
        }
    }

    /**
     * @param null  $key
     * @param null  $value
     * @param array $default
     * @return Value|null
     */
    public function field($key = null, $value = null, $default = [])
    {
        if ($key === null) {
            return $this->data($value, $default);
        } elseif ($value !== null) {
            return $this->_fields[$key] = $value;
        } else {
            return $this->_fields[$key] = new Value(['default' => $default, 'builder' => $this]);
        }
    }

    protected function getCode()
    {
        return $this->_code ?: 0;
    }

    protected function getMessage()
    {
        return $this->_message ?: '操作成功';
    }

    protected function getData()
    {
        if ($this->rtData !== null) {
            return $this->rtData;
        }
        $this->rtData = $this->buildData();
        if (!is_array($this->rtData)) {
            return $this->rtData;
        }
        $this->buildFields();
        return $this->rtData;
    }

    protected function buildData()
    {
        if ($this->_data instanceof Value) {
            return $this->_data->evaluate();
        }
        return isset($this->_data) ? $this->_data : [];
    }

    protected function buildFields()
    {
        foreach ($this->_fields as $field => $value) {
            if ($value instanceof Value) {
                $this->rtData[$field] = $value->evaluate();
                continue;
            }
            $this->rtData[$field] = $value;
        }
    }

    public function pushError($model)
    {
        array_push($this->errors, $model);
    }
}
